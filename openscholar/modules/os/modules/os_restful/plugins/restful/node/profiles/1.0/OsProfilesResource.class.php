<?php

/**
 * @file
 * Contains OsProfilesResource.
 */

class OsProfilesResource extends RestfulEntityBaseNode {

  /**
   * Overrides RestfulEntityBase::getPublicFields().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['description'] = array(
      'property' => 'body',
      'sub_property' => 'value',
    );

    $public_fields['address'] = array(
      'property' => 'field_address',
    );

    $public_fields['email'] = array(
      'property' => 'field_email',
    );

    $public_fields['prefix'] = array(
      'property' => 'field_prefix',
    );

    $public_fields['first_name'] = array(
      'property' => 'field_first_name',
    );

    $public_fields['middle_name'] = array(
      'property' => 'field_middle_name_or_initial',
    );

    $public_fields['last_name'] = array(
      'property' => 'field_last_name',
    );

    $public_fields['phone'] = array(
      'property' => 'field_phone',
    );

    $public_fields['professional_title'] = array(
      'property' => 'field_professional_title',
    );

    $public_fields['website'] = array(
      'property' => 'field_website',
    );

    $public_fields['photo'] = array(
      'property' => 'field_person_photo',
      'process_callbacks' => array(array($this, 'processPhoto')),
    );

    $public_fields['uuid'] = array(
      'property' => 'field_uuid',
    );

    $public_fields['restful'] = array(
      'callback' => array($this, 'restful'),
    );

    $public_fields['original_url'] = array(
      'property' => 'field_original_destination_url',
    );

    $public_fields['type'] = array(
      'property' => 'type',
    );

    $public_fields['author'] = array(
      'property' => 'author',
      'sub_property' => 'uid',
    );

    $public_fields['changed'] = array(
      'property' => 'changed',
    );

    return $public_fields;
  }

  /**
   * Overrides RestfulEntityBase::viewEntity().
   */
  public function viewEntity($id) {
    $values = parent::viewEntity($id);

    $wrapper = entity_metadata_wrapper('node', $id);

    if (isset($wrapper->field_uuid) && !$wrapper->field_uuid->value()) {
      // No UUID in the UUID field. Set it.
      $wrapper->field_uuid->set(md5($id));
      $wrapper->save();
    }

    // Add the UUID field to the returned values.
    $values['uuid'] = $wrapper->field_uuid->value();

    return $values;
  }

  /**
   * Process callback; Return the URI value of an image.
   */
  protected function processPhoto($value) {
    return array(
      'cropbox_height' => $value['cropbox_height'],
      'cropbox_width' => $value['cropbox_width'],
      'cropbox_x' => $value['cropbox_x'],
      'cropbox_y' => $value['cropbox_y'],
      'url' => file_create_url($value['uri']),
      'timestamp' => $value['timestamp'],
    );
  }

  /**
   * Notifying that the current feed was generated by restful module and not
   * restws module.
   *
   * @return bool
   */
  protected function restful() {
    return TRUE;
  }

  /**
   * Provide the url of the site.
   */
  protected function url() {
    return url('', array('absolute' => TRUE));
  }

  /**
   * overrides RestfulEntityBase::entityValidate().
   */
  public function entityValidate(\EntityMetadataWrapper $wrapper) {
    // No need to override the profiles that coming from OpenScholar
    // installations.
  }

  /**
   * Overrides RestfulEntityBase::getPublicFields().
   */
  public function checkPropertyAccess($op, $public_field_name, EntityMetadataWrapper $property_wrapper, EntityMetadataWrapper $wrapper) {
    $fields = array('field_uuid', 'field_original_destination_url', 'field_destination_url');
    $property_info = $property_wrapper->info();

    if (in_array($property_info['name'], $fields)) {
      // The fields access for the fields above should be handled via restful.
      return TRUE;
    }

    return parent::checkPropertyAccess($op, $public_field_name, $property_wrapper, $wrapper);
  }

}

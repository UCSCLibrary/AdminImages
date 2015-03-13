<?php
/**
 * An AdminImages Image 
 * 
 * @package AdminImages
 *
 */
class AdminImage extends Omeka_Record_AbstractRecord
{
    /*
     *@var int The record ID
     */
    public $id; 

    /*
     *@var string The slug of the collection
     */
    public $slug; 

    public $title;

    public $created_by_user_id;

}
?>
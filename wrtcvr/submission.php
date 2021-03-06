<?php
/**
 * Code to allow a student to submit his video
 *
 * @package   mod_wrtcvr
 * @copyright 2017 UPMC
 */
require_once(dirname(__FILE__).'/submission_form.php');

$form = new mod_wrtcvr_submission_form();

if($form->is_cancelled()) {
    $filepath = $CFG->tempdir.'/';
    $fileurl = $filepath.$_SESSION['file_url'];
    if(file_exists($fileurl)) {
        unlink($fileurl);
    }
    global $PAGE;
    $urltogo = new moodle_url('/course/view.php', array('id'=>$PAGE->course->id));
    redirect($urltogo);
} else if($fromform = $form->get_data()) {
    global $CFG;
    global $DB;
    global $USER;

    $filepath = $CFG->tempdir.'/';

    $fs = get_file_storage();

    if($previousvideo) {
        //Delete the previous file
        $previousvideofile = $DB->get_record('files', array('id'=>$previousvideo->fileid));

        $file = $fs->get_file($previousvideofile->contextid, $previousvideofile->component, $previousvideofile->filearea, $previousvideofile->itemid, $previousvideofile->filepath, $previousvideofile->filename);
        if ($file) {
            $file->delete();
        }
    }

    $fileinfo = array(
        'contextid' => $context->id,
        'component' => 'mod_wrtcvr',
        'filearea' => 'submission',
        'itemid' => intval(preg_split('/_/', $fromform->file_url)[0]),
        'filepath' => '/',
        'filename' => $fromform->file_url);

    $fileid = $fs->create_file_from_pathname($fileinfo, $filepath.$fromform->file_url)->get_id();

    if($previousvideo) {
        $previousvideo->fileid = $fileid;
        $DB->update_record('wrtcvr_submissions', $previousvideo);
    } else {
        $record = new stdClass();
        $record->assignment = $wrtcvr->id;
        $record->userid = $USER->id;
        $record->fileid = $fileid;
        $DB->insert_record('wrtcvr_submissions', $record);
    }

    unlink($filepath.$fromform->file_url);

    global $PAGE;
    $urltogo = new moodle_url('/course/view.php', array('id'=>$PAGE->course->id));
    redirect($urltogo, get_string('videohasbeenuploaded', 'mod_wrtcvr'), 10);
} else {
    $data = new stdClass();
    $data->file_url = $_SESSION['file_url'];
    $form->set_data($data);
}
?>

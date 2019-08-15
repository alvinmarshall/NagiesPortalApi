<?php


namespace App\common;

class AppConstant
{
    const TABLE_REPORT_IMAGE = "report_png";
    const TABLE_REPORT_PDF = "report";
    const TABLE_ASSIGNMENT_PDF = "assignment";
    const TABLE_ASSIGNMENT_IMAGE = "assignment_image";
    const FORMAT_IMAGE = "jpeg";
    const FORMAT_PDF = "pdf";
    const TABLE_STUDENT = "student";
    const TABLE_TEACHER = "teachers";
    const FIREBASE_MESSAGING_URL = "https://fcm.googleapis.com/fcm/send";
    const IOC_REQUEST = 'request';
    const IOC_STUDENTS_MODEL = 'students.model';
    const IOC_DATABASE = 'db';
    const IOC_TEACHERS_MODEL = 'teachers.model';
    const IOC_USER_MODEL = 'users.model';
    const IOC_FCM_SERVICE = 'fcm.service';
    const USER_PARENT = 'parent';
    const USER_TEACHER = 'teacher';
    const TABLE_CIRCULAR = 'circular';
    const DIR_CIRCULAR = './circular/uploads/';
    const TABLE_BILLING = 'billing';
    const DIR_BILLING = './billing/uploads';
}
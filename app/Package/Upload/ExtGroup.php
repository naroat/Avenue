<?php

namespace App\Package\Upload;
class ExtGroup
{
    /**
     * 黑名单
     *
     * @var array
     */
    /*public static $extBlackList = [
        'php', 'php5', 'php4', 'php3', 'php2', 'php1',
        'html', 'htm', 'phtml',
        'jsp', 'jspa', 'jspx', 'jsw', 'jspf', 'jhtml',
        'asp', 'aspx', 'asa', 'asax', 'ascx', 'ashx', 'asmx',
        'cer', 'swf'
    ];*/

    /**
     * 允许上传的格式
     *
     * @var array
     */
    public static $ext = [
        //图片
        'jpeg',
        'jpg',
        'png',
        'gif',
        'ico',
        //音频,
        'mp3',
        'ogg',
        'wav',
        'm3u',
        'm4a',
        'ra',
        //视频
        'mp4',
        'avi',
        'mpg',
        '3gp',
        'rmvb',
        'wmv',
        'flv',
        'mkv',
        'mov',
        //excel
        'xls',
        'xlsx',
        'xlsb',
        'xltm',
        'xltx',
        'xlsm',
        'xlam',
        //文档
        'pdf',
        'doc',
        'docx',
        //其他
        'apk',
    ];

    public static $extLink = [
        //图片类型
        'jpeg' => 'image/jpeg',
        'jpg' => ['image/jpg', 'image/jpeg'],
        'png' => 'image/png',
        'gif' => 'image/gif',

        //favicon
        'ico' => [
            'image/vnd.microsoft.icon',         //这是前端获取到文件类型
            'image/x-icon'                      //这是php获取到的文件类型
        ],

        //视频类型
        'mp4' => 'video/mp4',
        'avi' => 'video/avi',
        'mpg' => 'video/mpg',
        '3gp' => 'video/3gp',
        'rmvb' => 'video/rmvb',
        'wmv' => 'video/wmv',
        'mov' => 'video/quicktime',

        //音频
        'amr' => 'audio/amr',
        'mp3' => 'audio/mp3',
        'wav' => 'audio/x-wav',
        'm3u' => 'audio/x-mpegurl',
        'm4a' => 'audio/x-m4a',
        'ogg' => 'audio/ogg',
        'ra'  => 'audio/x-realaudio',

        //excel类型
        'xls' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],
        'xlsb' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],
        'xltm' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],
        'xltx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],
        'xlsm' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],
        'xlam' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'],

        //word
        'doc' => ['application/wps-writer', 'application/msword', 'application/zip'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],

        //PDF
        'pdf' => ['application/pdf'],

        //APK
        'apk' => ['application/vnd.android.package-archive', 'application/zip']
    ];
}
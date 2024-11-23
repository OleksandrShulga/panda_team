<?php

return [
    'admin-user' => [
        'title' => 'Users',

        'actions' => [
            'index' => 'Users',
            'create' => 'New User',
            'edit' => 'Edit :name',
            'edit_profile' => 'Edit Profile',
            'edit_password' => 'Edit Password',
        ],

        'columns' => [
            'id' => 'ID',
            'last_login_at' => 'Last login',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Password Confirmation',
            'activated' => 'Activated',
            'forbidden' => 'Forbidden',
            'language' => 'Language',
                
            //Belongs to many relations
            'roles' => 'Roles',
                
        ],
    ],

    'anchor' => [
        'title' => 'Anchors',

        'actions' => [
            'index' => 'Anchors',
            'create' => 'New Anchor',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'url_id' => 'Url',
            'email_id' => 'Email',
            
        ],
    ],

    'anchor' => [
        'title' => 'Anchors',

        'actions' => [
            'index' => 'Anchors',
            'create' => 'New Anchor',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'url_id' => 'Url',
            'email_id' => 'Email',
            'user_id' => 'User',
            
        ],
    ],

    'subcribe' => [
        'title' => 'Subcribes',

        'actions' => [
            'index' => 'Subcribes',
            'create' => 'New Subcribe',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            
        ],
    ],

    'subscribe' => [
        'title' => 'Subscribes',

        'actions' => [
            'index' => 'Subscribes',
            'create' => 'New Subscribe',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'url_from_user' => 'Url from user',
            'email_from_user' => 'Email from user',
            'url_id' => 'Url',
            'email_id' => 'Email',
            'user_id' => 'User',
            
        ],
    ],

    // Do not delete me :) I'm used for auto-generation
];
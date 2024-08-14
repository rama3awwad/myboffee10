<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | The file path to the Firebase credentials. This is the JSON file you
    | downloaded from your Firebase project.
    |
    */

    'credentials' => [
        'file' => env('FIREBASE_CREDENTIALS'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Project ID
    |--------------------------------------------------------------------------
    |
    | This is the Project ID of your Firebase project.
    |
    */

    'project_id' => env('FIREBASE_PROJECT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Firebase API Key
    |--------------------------------------------------------------------------
    |
    | The API Key of your Firebase project. This is used for various Firebase
    | services such as Firebase Cloud Messaging (FCM).
    |
    */

    'api_key' => env('FIREBASE_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Auth Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for Firebase Authentication.
    |
    */

    'auth' => [
        'enabled' => true, // Enable or disable Firebase Authentication
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Database Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for Firebase Realtime Database.
    |
    */

    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'), // You can add this to your .env if using Firebase Database
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for Firebase Cloud Messaging (FCM).
    |
    */

    'messaging' => [
        'enabled' => true, // Enable or disable FCM
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for Firebase Storage.
    |
    */

    'storage' => [
        'enabled' => true, // Enable or disable Firebase Storage
        'bucket' => env('FIREBASE_STORAGE_BUCKET'), // Add this to your .env if using Firebase Storage
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Analytics Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for Firebase Analytics.
    |
    */

    'analytics' => [
        'enabled' => false, // Enable or disable Firebase Analytics
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Firestore Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for Firebase Firestore.
    |
    */

    'firestore' => [
        'enabled' => false, // Enable or disable Firestore
    ],
];

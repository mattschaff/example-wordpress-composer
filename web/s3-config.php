<?php

define( 'S3_UPLOADS_BUCKET', getenv('S3_UPLOADS_BUCKET') );
define( 'S3_UPLOADS_REGION', getenv('S3_UPLOADS_REGION') ); // the s3 bucket region (excluding the rest of the URL)

// You can set key and secret directly:
define( 'S3_UPLOADS_KEY', getenv('S3_UPLOADS_KEY') );
define( 'S3_UPLOADS_SECRET', getenv('S3_UPLOADS_SECRET') );

// Or if using IAM instance profiles, you can use the instance's credentials:
define( 'S3_UPLOADS_USE_INSTANCE_PROFILE', true );
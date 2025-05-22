<?php

function has_uploaded_files( array $files ): bool {
    foreach ( $files['error'] as $err ) {
        if ( $err !== UPLOAD_ERR_NO_FILE ) {
            return true;
        }
    }
    return false;
}
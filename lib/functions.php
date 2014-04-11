<?php

function download($url) {
    return file_get_contents($url, false, stream_context_create(array(
        'http'	=> array(
            'header'	=>	"Referer: $url\r\n".
                            "User-Agent:Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.154 Safari/537.36\r\n",
        )
    )));
}
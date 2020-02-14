<?php

namespace Zamoroka\WebapiDocuments\Api;

/**
 * Interface PdfInterface
 */
interface PdfInterface
{
    /**
     * Upload Pdf
     *
     * @return string
     * @api
     */
    public function upload(): string;
}

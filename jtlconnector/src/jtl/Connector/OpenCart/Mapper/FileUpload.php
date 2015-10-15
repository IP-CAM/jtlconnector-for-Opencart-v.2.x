<?php
/**
 * @author Sven Mäurer <sven.maeurer@jtl-software.com>
 * @copyright 2010-2013 JTL-Software GmbH
 */

namespace jtl\Connector\OpenCart\Mapper;

class FileUpload extends BaseMapper
{
    protected $pull = [
        'id' => 'product_option_id',
        'productId' => 'product_id',
        'isRequired' => 'required',
        'i18ns' => 'FileUploadI18n'
    ];
}
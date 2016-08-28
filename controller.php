<?php

namespace Concrete\Package\CoverPicture7;

defined('C5_EXECUTE') or die('Access Denied.');

use Package;
use Exception;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Asset\AssetList;

class Controller extends Package
{
    protected $pkgHandle = 'cover_picture_7';
    protected $appVersionRequired = '5.7.5.9';
    protected $pkgVersion = '1.0.0';

    public function getPackageName()
    {
        return t('Cover Picture');
    }

    public function getPackageDescription()
    {
        return t('Adds a text covered by a picture');
    }

    public function install()
    {
        $pkg = parent::install();

        $ci = new ContentImporter();
        $ci->importContentFile($pkg->getPackagePath().'/install.xml');
    }

    public function upgrade()
    {
        $pkg = Package::getByHandle($this->pkgHandle);
        $ci = new ContentImporter();
        try {
            $ci->importContentFile($pkg->getPackagePath().'/install.xml');
        } catch (Exception $ex) {
        }

        parent::upgrade();
    }

    public function on_start()
    {
        $al = AssetList::getInstance();
        $asset = $al->register(
            'javascript-inline',
            'cover-picture',
            '',
            array(
                'combine' => false,
            ),
            $this
        );
        /* @var \Concrete\Core\Asset\JavascriptInlineAsset $asset */ 
        $asset->setAssetURL(<<<EOT
$(document).ready(function() {
    $('.cover-picture').on('click', function(event) {
        event.preventDefault();
        $(this).toggleClass('cover-picture-active');
    });
});
EOT
        );
}
}

<?php

namespace Concrete\Package\CoverPicture7\Block\CoverPicture;

use Concrete\Core\Block\BlockController;
use Concrete\Core\Block\View\BlockView;
use Less_Parser;
use Concrete\Core\Asset\CssAsset;
use Concrete\Core\Editor\LinkAbstractor;
use Concrete\Core\Support\Facade\Facade;
use File;

defined('C5_EXECUTE') or die('Access Denied.');

class Controller extends BlockController
{
    protected $btTable = 'btCoverPicture';
    protected $btWrapperClass = 'ccm-ui';
    protected $btInterfaceWidth = 600;
    protected $btInterfaceHeight = 645;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = true;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = false;
    protected $btCacheBlockOutputLifetime = 0;
    protected $btSupportsInlineAdd = false;
    protected $btSupportsInlineEdit = false;
    
    public function getBlockTypeName()
    {
        return t('Cover Picture');
    }

    public function getBlockTypeDescription()
    {
        return t('Cover picture where you can add additional text that shows up when you hover the picture.');
    }

    public function getContentEditMode()
    {
        return LinkAbstractor::translateFromEditMode($this->content);
    }

    public function add()
    {
        $this->set('overlayOpacity', 0.7);
        $this->set('overlayColor', '#999999');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Block\Content\Controller::save()
     */
    public function save($args)
    {
        $args += array(
            'content' => '',
            'fID' => 0,
            'overlayfID' => null,
            'overlayOpacity' => null,
            'overlayColor' => null,
        );
        $args['content'] = LinkAbstractor::translateTo($args['content']);
        $args['fID'] = (int) $args['fID'];
        $args['overlayfID'] = (int) $args['overlayfID'];
        $args['overlayOpacity'] = $args['overlayOpacityValue'] ? (float) $args['overlayOpacityValue'] : 0.7;
        $args['overlayColor'] = $args['overlayColor'] ? $args['overlayColor'] : '#999999';
        parent::save($args);
    }

    /**
     * @return string
     */
    protected function getCurrentTemplate()
    {
        $blockObject = $this->getBlockObject();

        return $blockObject ? (string) $blockObject->getBlockFilename() : '';
    }

    /**
     * Called every time the page is rendered. We hook into this method to check
     * if we have to regenerate the .less files.
     */
    public function on_page_view()
    {
        $app = isset($this->app) ? $this->app : Facade::getFacadeApplication();
        $config = $app->make('config');

        $this->requireAsset('javascript', 'jquery');

        // get current block template
        $template = $this->getCurrentTemplate();

        $bv = new BlockView($this->getBlockObject());
        $bv->setController($this);

        // build path to less file
        $blockPath = $bv->getBlockPath();
        if ($template === '') {
            $blockTemplateLessPath = $blockPath.'/view.less';
        } else {
            $blockTemplateLessPath = $blockPath.'/'.DIRNAME_BLOCK_TEMPLATES."/$template/view.less";
        }

        // there's a less file, check if we have to rebuild it
        if (file_exists($blockTemplateLessPath)) {
            $cacheDir = $config->get('concrete.cache.directory');
            $lessFileHash = md5($blockTemplateLessPath.$this->bID.filemtime($blockTemplateLessPath));
            $cssName = "cover-picture-$lessFileHash.css";
            $cachePath = "$cacheDir/$cssName";
            $cacheRel = REL_DIR_FILES_CACHE."/$cssName";

            // cache file doesn't exist, rebuild it
            if (!file_exists($cachePath)) {
                $parser = new Less_Parser(
                    array(
                        'cache_dir' => $cacheDir,
                        'compress' => true,
                        'sourceMap' => false,
                    )
                );
                $parser->ModifyVars(array(
                    'opacity' => $this->overlayOpacity,
                    'color' => $this->overlayColor,
                    'overlayPicture' => $this->overlayfID ? 'url('.File::getByID($this->overlayfID)->getRelativePath().')' : 'none',
                    'bID' => $this->bID,
                ));
                $parsed = $parser->parseFile($blockTemplateLessPath);
                $css = $parsed->getCss();
                file_put_contents($cachePath, $css);
            }
            $asset = new CssAsset($cssName);
            $asset->register(
                $cacheRel,
                array(
                    'local' => true,
                    'minify' => false,
                    'combine' => true,
                )
            );
            $asset->setAssetPath($cachePath);
            $asset->setAssetURL($cacheRel);
            $this->requireAsset($asset);
        }
        $this->requireAsset('javascript-inline', 'cover-picture');
    }

    public function view()
    {
        $fileObject = $this->fID ? File::getByID($this->fID) : null;
        $this->set('fileObject', $fileObject);
        $this->set('fileURL', $fileObject ? $fileObject->getURL() : null);
        $this->set('fileWidth', $fileObject ? $fileObject->getAttribute('width') : null);
        $this->set('fileHeight', $fileObject ? $fileObject->getAttribute('height') : null);
        $this->set('content', LinkAbstractor::translateFrom($this->content));
    }

    public function getSearchableContent()
    {
        return $this->content;
    }

}

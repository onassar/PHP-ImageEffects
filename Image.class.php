<?php

    // clean
    namespace ImageEffects;

    // fileinfo requirement
    if (!in_array('fileinfo', get_loaded_extensions())) {
        throw new \Exception('Fileinfo extension needs to be installed.');
    }

    // imagick requirement
    if (!in_array('imagick', get_loaded_extensions())) {
        throw new Exception('Imagick extension needs to be installed.');
    }

    // convert command (eg. php extension installed, but not Imagemagick)
    if (strstr(exec('type convert'), 'found')) {
        throw new \Exception(
            'Imagick needs to be installed. Try <sudo apt-get update> and ' .
            '<sudo apt-get install imagemagick>'
        );
    }

    // dependencies
    require_once 'Actions.class.php';
    require_once 'Filters.class.php';

    /**
     * Image
     *
     * @author    Oliver Nassar <onassar@gmail.com>
     * @see       <http://net.tutsplus.com/tutorials/php/create-instagram-filters-with-php/>
     * @thanks    <https://github.com/webarto/instagraph>
     * @example
     * <code>
     *     // library inclusion
     *     require_once APP . '/vendors/PHP-ImageEffects/Image.class.php';
     * 
     *     // instantiation with image path
     *     $image = (new ImageEffects\Image(APP . '/webroot/kittens.jpg'));
     * 
     *     // header definiton; squaring of image output
     *     header('Content-Type: image/jpeg');
     *     echo $image->lomo();
     *     exit(0);
     * </code>
     */
    class Image
    {
        /**
         * _effects
         * 
         * @var    Array
         * @access protected
         */
        protected $_effects = array(
            'bw',
            'gotham',
            'kelvin',
            'lomo',
            'nashville',
            'tilt',
            'toaster'
        );

        /**
         * _path
         * 
         * @var    String
         * @access protected
         */
        protected $_path;

        /**
         * _range
         * 
         * @var    Array
         * @access protected
         */
        protected $_range = array(
            'image/jpeg',
            'image/jpg',
            'image/png'
        );

        /**
         * _reserved
         * 
         * @var    String
         * @access protected
         */
        protected $_reserved;

        /**
         * _type
         * 
         * @var    String
         * @access protected
         */
        protected $_type;

        /**
         * __construct
         * 
         * Bails if the path contains invalid characters.
         * 
         * @todo   See description :P
         * @public
         * @param  String $path
         * @return void
         */
        public function __construct($path)
        {
            if (!is_file($path)) {
                throw new \Exception(
                    'Image *' . ($path) . '* is not a valid file.'
                );
            }
            $this->_path = $path;
            $this->_loadResource();
        }

        /**
         * __call
         * 
         * @access public
         * @return String $name
         * @return Array $arguments
         * @return String the raw binary of the altered-image
         */
        public function __call($name, Array $arguments)
        {
            // if it's an *invalid* effect
            if (in_array($name, $this->_effects) === false) {
                throw new \Exception(
                    '*' . ($name) . '* is an invalid effect.'
                );
            }

            // apply through <Filters>
            call_user_func_array(
                array('ImageEffects\Filters', $name),
                array($this->_reserved)
            );

            // read image from path
            $resource = (new \Imagick());
            $resource->readImage($this->_reserved);
            $blob = $resource->getImageBlob();

            // delete reserved path
            unlink($this->_reserved);

            // response with the raw binary
            return $blob;
        }

        /**
         * _getFileType
         * 
         * @access protected
         * @return String
         */
        protected function _getFileType()
        {
            if (!is_null($this->_type)) {
                return $this->_type;
            }
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $this->_type = finfo_file($finfo, $this->_path);
            return $this->_type;
        }

        /**
         * _loadResource
         * 
         * @access protected
         * @return void
         */
        protected function _loadResource()
        {
            // type enforcement
            $type = $this->_getFileType();
            $keys = array_keys($this->_range);
            if (!in_array($type, $keys)) {
                throw new \Exception(
                    'Invalid image type being processed. Image *' .
                    $this->_path . '* is of type *' . ($type) . '* and must ' .
                    'be one of the following: ' . implode(', ', $keys)
                );
            }

            // reserve a file on the disk (for <exec> manipulation)
            $this->_reserve();
        }

        /**
         * _reserve
         * 
         * @access protected
         * @return void
         */
        protected function _reserve()
        {
            // set up reserved path
            $rand = rand(10000, 99999);
            $reserved = ($this->_path) . '.' . ($rand);
            copy($this->_path, $reserved);
            $this->_reserved = $reserved;
        }
    }

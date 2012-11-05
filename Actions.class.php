<?php

    // clean
    namespace ImageEffects;

    /**
     * Actions
     *
     * @author    Oliver Nassar <onassar@gmail.com>
     * @thanks    <https://github.com/webarto/instagraph>
     * @abstract
     */
    abstract class Actions
    {
        /**
         * _execute
         * 
         * @protected
         * @static
         * @param     String $command
         * @return    void
         */
        protected static function _execute($command)
        {
            $command = escapeshellcmd($command);
            exec($command);
        }

        /**
         * colortone
         * 
         * @note   Not working
         *         Generates the following error:
         *             convert: magick/list.c:224: CloneImages: Assertion `images->signature == 0xabacadabUL' failed.
         *             Aborted
         * @public
         * @static
         * @param  String $path
         * @param  String $color
         * @param  String $level
         * @param  mixed $type (default: 0)
         * @return void
         */
        public static function colortone($path, $color, $level, $type = 0)
        {
            // convert arguments
            $args = array(
                '-clone 0',
                '-fill ' . ($color),
                '-colorize 100%',
                '-clone 0',
                '-colorspace gray ' . ($type === 0 ? '-negate': ''),
                '-compose blend',
                '-define compose:args=' . ($level) . ',' . (100 - $level),
                '-composite'
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                ($path);

            // runs and saves it to the existing path
            self::_execute($command);
        }

        /**
         * border
         * 
         * @public
         * @static
         * @param  String $path
         * @param  String $color (default: black)
         * @param  Integer $width (default: 20)
         * @return void
         */
        public static function border($path, $color = 'black', $width = 20)
        {
            // convert arguments
            $args = array(
                '-bordercolor ' . ($color),
                '-border ' . ($width) . 'x' . ($width)
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                ($path);

            // runs and saves it to the existing path
            self::_execute($command);
        }

        /**
         * frame
         * 
         * @public
         * @static
         * @param  String $path
         * @param  String $frame
         * @return void
         */
        public static function frame($path, $frame)
        {
            // dimensions
            list($width, $height) = getimagesize($path);

            // convert arguments
            $args = array(
                __DIR__ . '/' . ($frame),
                '-resize ' . ($width) . 'x' . ($height) . '!',
                '-unsharp 1.5x1.0+1.5+0.02)',
                '-flatten'
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                ($path);

            // runs and saves it to the existing path
            self::_execute($command);
        }

        /**
         * vignette
         * 
         * @see    <http://www.imagemagick.org/Usage/canvas/>
         * @public
         * @static
         * @param  String $path
         * @param  String $inner (default: none) Inner colour
         * @param  String $outer (default: black) Outer colour
         * @return void
         */
        public static function vignette($path, $inner = 'none', $outer = 'black')
        {
            // dimensions
            list($width, $height) = getimagesize($path);
            $crop = array(
                'x' => floor($width * 1.5),
                'y' => floor($height * 1.5)
            );

            // convert arguments
            $args = array(
                '-size ' . ($crop['x']) . 'x' . ($crop['y']),
                'radial-gradient:' . ($inner) . '-' . ($outer),
                '-gravity center',
                '-crop ' . ($width) . 'x' . ($height) . '+0+0 +repage'
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                '-compose multiply -flatten ' .
                ($path);

            // runs and saves it to the existing path
            self::_execute($command);
        }
    }

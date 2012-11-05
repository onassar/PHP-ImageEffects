<?php

    // clean
    namespace ImageEffects;

    /**
     * Filters
     *
     * @author    Oliver Nassar <onassar@gmail.com>
     * @thanks    <https://github.com/webarto/instagraph>
     * @abstract
     */
    abstract class Filters
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
         * bw
         * 
         * @works
         * 
         * @public
         * @static
         * @param  String $path
         * @return void
         */
        public static function bw($path)
        {
            // arguments for lomo effect
            $args = array(
                '-type Grayscale'
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                ($path);
            self::_execute($command);
        }

        /**
         * gotham
         * 
         * @works
         * 
         * @public
         * @static
         * @param  String $path
         * @return void
         */
        public static function gotham($path)
        {
            // arguments for lomo effect
            $args = array(
                '-modulate 120,10,100',
                '-fill #222b6d',
                '-colorize 20',
                '-gamma 0.5',
                '-contrast',
                '-contrast'
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                ($path);
            self::_execute($command);

            // apply border on the image
            Actions::border($path);
        }

        /**
         * nashville
         * 
         * @busted
         * 
         * @public
         * @static
         * @param  String $path
         * @return void
         */
        public static function nashville($path)
        {
            // colorize
            Actions::colortone($path, '#222b6d', 100, 0);
            Actions::colortone($path, '#f7daae', 100, 1);

            // arguments for nashville effect
            $args = array(
                '-contrast',
                '-modulate 100,150,100',
                '-auto-gamma'
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                ($path);
            self::_execute($command);

            // apply frame to image
            Actions::frame($path, 'nashville');
        }

        /**
         * kelvin
         * 
         * @kinda colours seem off
         * 
         * @public
         * @static
         * @param  String $path
         * @return void
         */
        public static function kelvin($path)
        {
            // dimensions
            list($width, $height) = getimagesize($path);

            // arguments for tilt effect
            $args = array(
                '-auto-gamma',
                '-modulate 120,50,100',
                '-size ' . ($width) . 'x' . ($height),
                '-fill rgba(255,153,0,0.5)',
                '-draw "rectangle 0,0 ' . ($width) . 'x' . ($height) . '"',
                '-compose blur'
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                ($path);
            self::_execute($command);

            // apply frame to image
            Actions::frame($path, 'kelvin');
        }

        /**
         * lomo
         * 
         * @works
         * 
         * @public
         * @static
         * @param  String $path
         * @return void
         */
        public static function lomo($path)
        {
            // arguments for lomo effect
            $args = array(
                '-channel R',
                '-level 33%',
                '-channel G',
                '-level 33%'
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                ($path);
            self::_execute($command);

            // apply vignette on the image
            Actions::vignette($path);
        }

        /**
         * tilt
         * 
         * @busted
         * 
         * @public
         * @static
         * @param  String $path
         * @return void
         */
        public static function tilt($path)
        {
            // arguments for tilt effect
            $args = array(
                '-gamma 0.75',
                '-modulate 100,130',
                '-contrast +clone',
                '-sparse-color Barycentric "0,0 black 0,%h white"',
                '-function polynomial 4,-4,1',
                '-level 0,50%',
                '-compose blur',
                '-set option:compose:args 5',
                '-composite'
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                ($path);
            self::_execute($command);
        }

        /**
         * toaster
         * 
         * @busted
         * 
         * @public
         * @static
         * @param  String $path
         * @return void
         */
        public static function toaster($path)
        {
            // colorize
            Actions::colortone($path, '#330000', 100, 0);

            // arguments for toaster effect
            $args = array(
                '-modulate 150,80,100',
                '-gamma 1.2',
                '-contrast',
                '-contrast'
            );
            $command = 'convert ' . ($path) . ' ' .
                implode(' ', $args) . ' ' .
                ($path);
            self::_execute($command);

            // apply vignettes to the image
            Actions::vignette($path, 'none', 'LavenderBlush3');
            Actions::vignette($path, '#ff9966', 'none');
        }
    }

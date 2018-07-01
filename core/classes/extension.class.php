<?php
/**
 *Using for load extension from extension folder
 *finalized to denie users expirience
 **/
final class Extension
{
    protected $extension = null;

    public function __construct( $extension_name )
    {
        /**
         *Load extension when initialized: $my_extension_name = new Extension('my_extension_name');
         **/
        if ( $extension_name ) {

            if(strpos( $extension_name, '.' ) !== false) {

                $extension_name = explode('.', $extension_name);

                $extension_name = $extension_name[0];
            }

            $this->load_extension( $extension_name );
        }

    }

    protected function load_extension( $extension_name )
    {
        /**
         *First check extension folder for users extensions classes, if not found searching in library for using defaults core libraries
         **/
        $ext_file = ROOT . '/extensions/' . strtolower( $extension_name ) . '.class.php';

        $lib_file = ROOT . '/core/library/' . strtolower( $extension_name ) . '.class.php';

        if (file_exists( $ext_file )) {

            require_once $ext_file;

            $this->extension = new $extension_name;

        } elseif (file_exists( $lib_file )) {

            require_once $lib_file;

            $this->extension = new $extension_name;

        } else return false;
    }

    /**
     *Use users extension methods as extension object methods (or load if first call)
     **/
    public function __get( $property )
    {
        if ( !$this->extension ) {

            $this->load_extension( $property );

        } elseif ( method_exists( $this->extension, $property ) ) $this->extension->$property();

        return $this;
    }
}
?>

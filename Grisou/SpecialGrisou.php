<?php
class SpecialGrisou extends SpecialPage {
        function __construct() {
                parent::__construct( 'Grisou' );
        }
	
        function execute( $par ) {
                $request = $this->getRequest();
                $output = $this->getOutput();
		
                $this->setHeaders();
 
                # Get request data from, e.g.
                $param = $request->getText( 'param' );
 
                # Do stuff
                # ...
                $wikitext = $this->content();
                $output->addHTML( $wikitext );
				$output->addModules( 'ext.grisouCss' );
				$output->addModules( 'ext.grisouJs' );
        }
        
        function content(){
			$out .= '<script type="text/javascript" src="/extensions/Grisou/includes/js/contributions.js"></script>';
		    $out .= "<form id='grisou-form' class='Grisou'>
				    <div id='question'>
					    <h2>Enter the username of the contributor:</h2>
					    <input type='text' name='user' />
					    <h2>Enter the url for the wikipedia web site the contributor has contributed to :</h2>
					    <input type='text' name='wiki' value='fr.wikipedia.org' />
					    <input type='hidden' name='page' value='base' />
					    <input type='button' value='Soumettre' id='grisou-submit' />
				    </div>
			    </form>
			
			    <div id='grisou-result'></div>";
				return $out;
	    }
}

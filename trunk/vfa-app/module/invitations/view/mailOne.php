<?php 
	// echo $this->oViewShow->show();
	echo "Mon premier mail\n";
	echo "Sur plusieurs lignes\n";
	echo "Caractères spéciaux : aàeéêèçùï€\n";
	echo "ECHO Lien vers l'invitation : ".plugin_vfa::generateURLInvitation($this->oInvit)."\n";
	print "PRINT Lien vers l'invitation : ".plugin_vfa::generateURLInvitation($this->oInvit)."\n";
	echo "Fin du test";
?>

<?php
/*
Classe entierement libre, ses methodes sont appelees en utilisant le prefixe app dans vos templates
Dans votre template exemple:
<span app:methode="valeur">texte</span>
Ici on va appeler la methode _methode de cette classe
au niveau du retour vous devez retourner un tableau avec les cles suivantes:

begin : avant l'element html [begin]<element_html>texte</element_html>
after : avant l'element html <element_html>texte</element_html>[after]

content : avant l'element html <element_html [content]>texte</element_html>

begin_content : avant l'element html <element_html>[begin]texte</element_html>
after_content : avant l'element html <element_html>texte[after]</element_html>

*/
class plugin_tplapp{
	

	public function _htmlentities($valeur){
		
		return array(
			'before_content'=> '<?php echo htmlentities(',
			'after_content' => ');?>',
		);
	}	

}

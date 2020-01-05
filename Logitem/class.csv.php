<?php
class CSV
{
	
	static function Export($datas,$filename)
	{
		header('Content-type: text/csv;');
		header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
		$i=0;
		foreach ($datas as $d) {
			if($i==0){
				echo '"'.implode('";"',array_keys($d)).'"'."\n";
			}
				echo '"'.implode('";"',$d).'"'."\n";
				$i++;
		}
	}
}

?>
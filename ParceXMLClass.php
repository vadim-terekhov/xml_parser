<?php
namespace ParceXML;
use PDO;

class ParceXMLClass {
		private $link;
		private $xml;
		private $path_to_xml;
		private $mas = [];
		private $table;
		
		public function __construct(){
			require_once(__DIR__.'\db.php');
			$this->table = $table;
			$this->link = new PDO($dsn,$user,$pas,$opt);
		}
		public function set_path_XML($path){
			$this->path_to_xml = $path;
		}
		
		private function getXML(){
			if (!empty($this->path_to_xml)){
				$this->xml = simplexml_load_file($this->path_to_xml);
			}else{
				echo 'Путь до файла XML не указан';
				die;
			}
		}
		
		private function query(){
			$res = $this->link->prepare("SELECT * FROM ".$this->table);
			$res->execute();
			return $res->fetchAll();
			
		}
		
		public function insert_in_base(){
			$this->link->exec("TRUNCATE ".$this->table);
			$this->getXML();
			foreach($this->xml as $val){
				$paketout = $val->PacketOut_UID;
				$rowstate = $val->RowState_UID;
				$id_category = $val->Group_UID;
				$name = $val->Group_ByName;
				$code = $val->Code;
				$id_parent = $val->Parent_UID;
				
				$row = $this->link->prepare("INSERT INTO market SET paketout=?,rowstate=?,id_category=?,name=?,code=?,id_parent=?");
				$row->execute([$paketout,$rowstate,$id_category,$name,$code,$id_parent]);
			}
		}
	
		
		public function sort_mas(){
			$sql = $this->query();
			foreach($sql as $val){
				$this->mas[$val['id_parent']][] = $val;
			}
			return $this->mas;
		}
		
		public function view_catalog(array $arr, $id_parent = '00000000-0000-0000-0000-000000000000'){
			if (!isset($arr[$id_parent])){
				return;
			}
			echo '<ul>';
				foreach($arr[$id_parent] as $val){
					echo "<li>".$val['name'];
					$this->view_catalog($arr,$val['id_category']);
					echo "</li>";
				}
			echo '</ul>';
		}
}
?>
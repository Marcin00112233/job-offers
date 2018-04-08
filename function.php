<?php
class functions{
	private $address; //zmienna przechowuje adres strony olx
	private $initial_sequence; //zmienna przechowuje ciąg początkowy
	private $final_sequence; //zmienna przechowuje ciąg końcowy
	
	private $pos; //zmienna indeksu bufora
	private $buff; //bufor
	private $link; //zmienna przechowująca link oferty olx
	private $condition = false; //warunek
	

	public function get_offers($domena){
	
	}
	
	//funkcja odpowiada za połączenie z bazą danych.
	public function connect_db(){
	$connect = mysql_connect('localhost','root','') or die ('bląd połączenia z bazą danych'); //połączenie z serwerem baz danych
	mysql_select_db('robot-praca') or die ('nieprawidłowa baza danych'); //wybranie bazy
	}
	
	public function get_data($sql){
	$result = mysql_query($sql) or die ('błąd zapytania'.mysql_error());
	$row = array();
	while($row = mysql_fetch_array($result)){
	}
	}
	
	public function set_data($sql){
	$result = mysql_query($sql)or die ('błąd zapytania');
	}
	
	public function add_link($link){
	set_data('Insert into links(id,link)values(NULL,'.$link.')');
	
	}
	
	public function check_link($link){}
	/*prywatna funkcja pget_link_olx() ma za zadanie pobrać stronę w formiacie string załadowaną wcześniej do bufora i wyciągnąć z niej linki kierujące do ofert pracy. Funkcja posługuje się ustawionymi wcześniej zmiennymi. Zwraca link do oferty*/
	private function pget_link_olx(){
		$this->buff = file_get_contents($this->address); //pobranie zawartości strony do zmiennej buff
		$this->pos = strpos($this->buff,$this->initial_sequence); //ustalenie pozycji ciągu początkowego w ciągu bufora
		$this->link = $this->buff[$this->pos]; //załadowanie znaku o indeksie początkowym
		do{ //wykonuj
			$this->pos++; //zwiększenie indeksu o 1
			$this->link = $this->link.$this->buff[$this->pos]; //załadowanie znaku o kolejnym indeksie
			if($this->buff[$this->pos]=="<"){ //jeżeli znak z powyższego indeksu równa się "<" to
				$this->pos++; //zwiększenie indeksu o 1
				$this->link = $this->link.$this->buff[$this->pos]; //załadowanie znaku o kolejnym indeksie
				if($this->buff[$this->pos]=="/"){ //jeżeli znak z powyższego indeksu równa się "/" to
					$this->pos++; //zwiększenie indeksu o 1
					$this->link = $this->link.$this->buff[$this->pos];//załadowanie znaku o kolejnym indeksie
					if($this->buff[$this->pos]=="a"){//jeżeli znak z powyższego indeksu równa się "a" to
						$this->pos++;//zwiększenie indeksu o 1
						$this->link = $this->link.$this->buff[$this->pos];//załadowanie znaku o kolejnym indeksie
						if($this->buff[$this->pos]==">"){//jeżeli znak z powyższego indeksu równa się ">" to
							$this->condition = true; // warunek = false
						}
					}
				}
			}
	

		}while(!$this->condition); //dopuki warunek nie osiągnie fałszu
		return $this->link; //zwróć zmienną link
	}
	
/*funkcja pobiera z linku sam adres jako parametr przekazywany jest link natomiast zwracany $address*/	
	public function get_address($link){ 
		$ciag = '"http'; //załadowanie początku adresu
		$pos1 = strpos($link,$ciag); //wyciągnięcie położenia pierwszego znaku początku adresu w linku i załadowanie do pos1
		$pos1++;      //inkrementacja pos1
		$address = $link[$pos1]; //przypisanie znaku z $link o adresie $pos1 do zmiennej $address
		$warunek = false; 
		do{                     //wykonuj
			$pos1++;            //inkrementacja indeksu linku
			if($link[$pos1]=='"') $warunek = true; //jeżeli znak spod rozpatrywanego indeksu = znak końca adresu to warunek = true co powoduje zakończenie pętli
			else $address = $address.$link[$pos1]; //w przeciwnym wypadku do poprzedniej zawartości adresu dopisywany jest kolejny znak
		}while(!$warunek); //dopóki warunek jest false
		return $address; //funckja zwraca adres
	}
	
/*funkcja get_content pobiera fragment tekstu o początku w zmiennej $initial_sequence i końcu w zmiennej $final_sequence z url podanego w zmiennej $address*/
	public function get_content($address,$initial_sequence,$final_sequence){
		$tresc = file_get_contents($address); //załadowanie zawartości url ($address) do zmiennej $ tresc
		$pos1 = strpos($tresc, $initial_sequence); //instrukcja zwraca do zmiennej $pos1 numer indeksu pierwszego znaku ciągu $initial_sequence w zawartości $tresc
		$content = ""; //zmienna $content ma przechowywać wynikowy ciąg na końcu jest zwracana do programu. tu jest inicjalizowana pustym łańcuchem.
		$i = 1; //zmienna $i która robi za indeks łańcucha finala_sequence.
		
		while($i != strlen($final_sequence)){ //pętla dopóki i jest różne od ostatniego indeksu $final_sequence to wykonuje:
		
			
			if($tresc[$pos1]==$final_sequence[$i]){ //jeżeli znak z indeksu $pos1 jest równy znakowi $final_sequence spod indeksu $i to:
				$content=$content.$tresc[$pos1]; //wynikowy ciąg $content = poprzednia jego zawartość + znak spod $tresc[$pos1]
				$pos1++; //inkrementacja $pos1++
				$i++; //inkrementacja $i
				
			}
			else{ //jeżeli znak z indeksu $pos1 nie jest równy znakowi $final_sequence spod indeksu $i to:
				$content=$content.$tresc[$pos1];  //wynikowy ciąg $content = poprzednia jego zawartość + znak spod $tresc[$pos1]
				$pos1++; //inkrementacja $pos1++
				$i = 1; //inkrementacja $i
			}
			
		}
		
		return $content; //zwrócenie ciągu wynikowego do programu wywołującego.
	}
}
?>
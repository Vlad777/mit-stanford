<?php
require_once('pdo_connect.php');
$stopwords = array("","iv","iii","amp","ii","'ll","'ve","I","a","able","about","above","abst","accordance","according","accordingly","across","act","actually","added","adj","affected","affecting","affects","after","afterwards","again","against","ah","ain.t","all","allow","allows","almost","alone","along","already","also","although","always","am","among","amongst","amoungst","amount","an","and","announce","another","any","anybody","anyhow","anymore","anyone","anything","anyway","anyways","anywhere","apart","apparently","appear","appreciate","appropriate","approximately","are","aren","aren't","arent","aren.t","arise","around","as","aside","ask","asking","associated","at","auth","available","away","awfully","a.s","b","back","be","became","because","become","becomes","becoming","been","before","beforehand","begin","beginning","beginnings","begins","behind","being","believe","below","beside","besides","best","better","between","beyond","bill","biol","both","bottom","brief","briefly","but","by","c","ca","call","came","can","can't","cannot","cant","can.t","cause","causes","certain","certainly","changes","clearly","co","com","come","comes","con","concerning","consequently","consider","considering","contain","containing","contains","corresponding","could","couldn't","couldnt","couldn.t","course","cry","currently","c.mon","c.s","d","date","de","definitely","desc","describe","described","despite","detail","did","didn't","didn.t","different","do","does","doesn't","doesn.t","doing","don't","done","don.t","down","downwards","due","during","e","each","ed","edu","effect","eg","eight","eighty","either","eleven","else","elsewhere","empty","end","ending","enough","entirely","especially","et","et-al","etc","even","ever","every","everybody","everyone","everything","everywhere","ex","exactly","example","except","f","far","few","ff","fifteen","fifth","fify","fill","find","fire","first","five","fix","followed","following","follows","for","former","formerly","forth","forty","found","four","from","front","full","further","furthermore","g","gave","get","gets","getting","give","given","gives","giving","go","goes","going","gone","got","gotten","greetings","h","had","hadn't","hadn.t","happens","hardly","has","hasn't","hasnt","hasn.t","have","haven't","haven.t","having","he","he'd","he'll","he's","hed","hello","help","hence","her","here","here's","hereafter","hereby","herein","heres","hereupon","here.s","hers","herself","hes","he.s","hi","hid","him","himself","his","hither","home","hopefully","how","how's","howbeit","however","hundred","i","i'd","i'll","i'm","i've","id","ie","if","ignored","im","immediate","immediately","importance","important","in","inasmuch","inc","indeed","index","indicate","indicated","indicates","information","inner","insofar","instead","interest","into","invention","inward","is","isn't","isn.t","it","it'll","it's","itd","its","itself","it.d","it.ll","it.s","i.d","i.ll","i.m","i.ve","j","just","k","keep","keeps","kept","kg","km","know","known","knows","l","largely","last","lately","later","latter","latterly","least","less","lest","let","let's","lets","let.s","like","liked","likely","line","little","look","looking","looks","ltd","m","made","mainly","make","makes","many","may","maybe","me","mean","means","meantime","meanwhile","merely","mg","might","mill","million","mine","miss","ml","more","moreover","most","mostly","move","mr","mrs","much","mug","must","mustn't","my","myself","n","na","name","namely","nay","nd","near","nearly","necessarily","necessary","need","needs","neither","never","nevertheless","new","next","nine","ninety","no","nobody","non","none","nonetheless","noone","nor","normally","nos","not","noted","nothing","novel","now","nowhere","o","obtain","obtained","obviously","of","off","often","oh","ok","okay","old","omitted","on","once","one","ones","only","onto","or","ord","other","others","otherwise","ought","our","ours","ourselves","out","outside","over","overall","owing","own","p","page","pages","part","particular","particularly","past","per","perhaps","placed","please","plus","poorly","possible","possibly","potentially","pp","predominantly","present","presumably","previously","primarily","probably","promptly","proud","provides","put","q","que","quickly","quite","qv","r","ran","rather","rd","re","readily","really","reasonably","recent","recently","ref","refs","regarding","regardless","regards","related","relatively","research","respectively","resulted","resulting","results","right","run","s","said","same","saw","say","saying","says","sec","second","secondly","section","see","seeing","seem","seemed","seeming","seems","seen","self","selves","sensible","sent","serious","seriously","seven","several","shall","shan't","she","she'd","she'll","she's","shed","shes","should","shouldn't","shouldn.t","show","showed","shown","showns","shows","side","significant","significantly","similar","similarly","since","sincere","six","sixty","slightly","so","some","somebody","somehow","someone","somethan","something","sometime","sometimes","somewhat","somewhere","soon","sorry","specifically","specified","specify","specifying","still","stop","strongly","sub","substantially","successfully","such","sufficiently","suggest","sup","sure","system","t","take","taken","taking","tell","ten","tends","th","than","thank","thanks","thanx","that","that'll","that's","that've","thats","that.s","the","their","theirs","them","themselves","then","thence","there","there'll","there's","there've","thereafter","thereby","thered","therefore","therein","thereof","therere","theres","thereto","thereupon","there.s","these","they","they'd","they'll","they're","they've","theyd","theyre","they.d","they.ll","they.re","they.ve","thickv","thin","think","third","this","thorough","thoroughly","those","thou","though","thoughh","thousand","three","throug","through","throughout","thru","thus","til","tip","to","together","too","took","top","toward","towards","tried","tries","truly","try","trying","ts","twelve","twenty","twice","two","t.s","u","un","under","unfortunately","unless","unlike","unlikely","until","unto","up","upon","ups","us","use","used","useful","usefully","usefulness","uses","using","usually","v","value","various","very","via","viz","vol","vols","vs","w","want","wants","was","wasn't","wasn.t","way","we","we'd","we'll","we're","we've","wed","welcome","well","went","were","weren't","weren.t","we.d","we.ll","we.re","we.ve","what","what'll","what's","whatever","whats","what.s","when","when's","whence","whenever","where","where's","whereafter","whereas","whereby","wherein","wheres","whereupon","wherever","where.s","whether","which","while","whim","whither","who","who'll","who's","whod","whoever","whole","whom","whomever","whos","whose","who.s","why","why's","widely","will","willing","wish","with","within","without","won't","wonder","won.t","words","world","would","wouldn't","wouldn.t","www","x","y","yes","yet","you","you'd","you'll","you're","you've","youd","your","youre","yours","yourself","yourselves","you.d","you.ll","you.re","you.ve","z","zero");
global $dbh;

execQuery("TRUNCATE TABLE autocomplete");

function multiexplode($delimiters, $string) {
    $ready = str_replace($delimiters, $delimiters[0], $string);
    $launch = explode($delimiters[0], $ready);
    return  $launch;
}


$all_courses = fetchAll("SELECT title, long_desc, category FROM course_data");
foreach ($all_courses as $course)
{
  $course_flag = NULL;
	$words = array_diff(multiexplode(str_split(' ,.;:=&()[]{}<>|\\!?/"\'`0123456789'), strtolower($course['title'] . ' ' . $course['long_desc'] . ' ' . $course['category'])), $stopwords);
	//print_r($words);
	foreach($words as $word)
	{
		if (is_null($course_flag[$word])) { $course_flag[$word] = 1; }
		try 
		{
			$word_data = fetchAll("SELECT id, course_count, word_count FROM autocomplete WHERE word = '$word'");
			if (empty($word_data))
			{
				echo "\nFirst time for $word";
				$qrm = $dbh->prepare("INSERT INTO autocomplete (word, course_count, word_count) VALUES (?, 1, 1)");
				$course_flag[$word] = 0;
				$qrm->execute(array($word));
			}
			else
			{
				echo "\nUpdating for $word (" . $course_flag[$word] . " " .  $word_data[0]['id'] . ")";
				$qrm = $dbh->prepare("UPDATE autocomplete SET course_count = course_count + ?, word_count = word_count + 1 WHERE id = ?");
				$qrm->execute(array($course_flag[$word], $word_data[0]['id']));
				$course_flag[$word] = 0;
			}
		}
		catch (PDOException $err)
		{
		    $err->getMessage();
			echo $err;
		}
	}
}

//table: id, word, course_count, word_count
?>

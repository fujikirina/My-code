<?php
function h($str){
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function colorIcon($ci){
  $colors = [
    ['ホワイト','whiteIcon'],
    ['ブラック','blackIcon'],
    ['レッド(オレンジ)','redIcon'],
    ['ブルー(グレー)','blueIcon'],
    ['クリーム','creamIcon'],
    ['チョコレート','chocoIcon']
  ];
  foreach($colors as list($str,$icon)){
    if(empty($ci)){return 'd-none';
    }elseif($ci==$str){return 'd-inline '.$icon;
    }
  }
}

<?php
// ＜アルゴリズムの注意点＞
// アルゴリズムではこれまでのように調べる力ではなく物事を論理的に考える力が必要です。
// 検索して答えを探して解いても考える力には繋がりません。
// まずは検索に頼らずに自分で処理手順を考えてみましょう。


// 「algorithm5」で作成したポーカープログラムにジョーカーを追加してください。
// ジョーカー１枚のみ、suitをjoker、numberを0と表す。
// 上記以外は不正として処理してください。

// 追加された役
// 「フォーカード」＋ジョーカーは「ファイブカード」

// 判定は強い役を優先してください。組み合わせの強さ順は以下とする。
// ロイヤルストレートフラッシュ > ストレートフラッシュ > ファイブカード > フォーカード > フルハウス > フラッシュ > ストレート > スリーカード > ツーペア > ワンペア
// ジョーカーが出た時点で最低でも「ワンペア」となること


// 手札
$cards = [
    ['suit'=>'heart', 'number'=>1],
    ['suit'=>'joker', 'number'=>0],
    ['suit'=>'heart', 'number'=>13],
    ['suit'=>'heart', 'number'=>12],
    ['suit'=>'heart', 'number'=>11],
];

function judge($cards) {
  //最終的な役を決める変数。
  $yaku = "なし";

  //カードの不正チェック
  for ( $i = 0 ; $i <= 4 ; $i++ ){
    //スート、数字不正判断
    if (
      ($cards[$i]["suit"] != "heart" &&
       $cards[$i]["suit"] != "spade" &&
       $cards[$i]["suit"] != "diamond" &&
       $cards[$i]["suit"] != "club" &&
       $cards[$i]["suit"] != "joker") ||
      ($cards[$i]["number"] < 0 ||
       $cards[$i]["number"] > 13)
    )
    {
      $yaku = "不正";
    };
    //jokerが０でない場合の不正判断
    if ( $cards[$i]["suit"] == "joker" || $cards[$i]["number"] == 0){
      if ( $cards[$i]["suit"] != "joker" || $cards[$i]["number"] != 0){
        $yaku = "不正";
      }
    };
    //同じカードの有無についての不正判断
    for ( $j = $i+1 ; $j <= 4 ; $j++ ){
      $card1 = $cards[$i]["suit"].$cards[$i]["number"];
      $card2 = $cards[$j]["suit"].$cards[$j]["number"];
      if ( $card1 == $card2 ){
        $yaku = "不正";
      }
    }
  };


  //不正でないのならば、役を判定する。
  if ( $yaku == "不正" ){
    print "手札は不正";
  }
  else
  {
    //スートと番号それぞれの配列を作る。
    $suits = [];
    $numbers = [];
    for ( $i = 0 ; $i <= 4 ; $i++ ){
      $suit = $cards[$i]["suit"];
      $suits[] = $suit;
      //ex) $suits = ["heart","spade","diamond"～];
      $number = $cards[$i]["number"];
      $numbers[] = $number;
      //ex) $numbers = [1,10,13～];
    }
    //$suitsにjokerを先頭にする。
    for ( $i = 0 ; $i <= 4 ; $i++ ){
      if ($suits[$i] == "joker"){
        $joker = $suits[$i];
        $suits[$i] = $suits[0];
        $suits[0] = $joker;
      }
    }
    //$numbersの順番を昇順にする。
    for ( $i = 0 ; $i <= 3 ; $i++ ){
      for ( $j = $i+1 ; $j <= 4 ; $j++ ){
        if ( $numbers[$i] > $numbers[$j] ){
          $n = $numbers[$i];
          $numbers[$i] = $numbers[$j];
          $numbers[$j] = $n;
        }
      }
    }

    #jokerを含むときの条件分岐
    if ($numbers[0] == 0 ){
      //jokerを含むとき
      //$numbersの要素内の同じ値を数える配列の作成。
      //ex)$numbers=[1,1,2,3] ならば $same=[1]
      //ex)$numbers=[1,1,2,2] ならば $same=[1,2]　など、、、
      $same = [];
      for ( $i = 1 ; $i <= 3 ; $i++ ){
        for ( $j = $i+1 ; $j <= 4 ; $j++ ){
          if ( $numbers[$i] == $numbers[$j] ){
            $same[] = $numbers[$i];
          }
        }
      }
      //$sameの配列数に応じて役を決定する。
      switch (count($same)){
        case 0:
          $yaku = "ワンペア";
          break;
        case 1:
          $yaku = "スリーカード";
          break;
        case 2:
          $yaku = "フルハウス";
          break;
        case 3:
            $yaku = "フォーカード";
            break;
        case 6:
            $yaku = "ファイブカード";
            break;
      }

      //フラッシュの条件設定
      $flash = $suits[1] == $suits[2] &&
               $suits[1] == $suits[3] &&
               $suits[1] == $suits[4] ;
      //フラッシュの役決定
      if ( $flash ){
        $yaku = "フラッシュ";
      }

      //ストレートの役決定
      $n = $numbers[1];
      $st0 = [
        [0 , $n , $n+1 , $n+2 , $n+3],
        [0 , $n , $n+1 , $n+2 , $n+4],
        [0 , $n , $n+1 , $n+3 , $n+4],
        [0 , $n , $n+2 , $n+3 , $n+4]
      ];
      for ( $i = 0 ; $i <= 3 ; $i++){
        if ( $numbers == $st0[$i] ){
          $yaku = "ストレート";
          // ストレートフラッシュの役決定
          if( $flash ){
            $yaku = "ストレートフラッシュ";
          }
        }
      }

      //ロイヤルストレートフラッシュの条件設定
      $royal = [
        [0,10,11,12,13],
        [0,1,11,12,13],
        [0,1,10,12,13],
        [0,1,10,11,12]
      ];
      //ロイヤルストレートフラッシュの役決定
      for ( $i = 0 ; $i <= 3 ; $i++){
        if ( $flash && $numbers == $royal[$i] ){
          $yaku = "ロイヤルストレートフラッシュ";
        }
      }

    }
    else
    {
      //jpkerを含まないとき
      //$numbersの要素内の同じ値を数える配列の作成。
      //ex)$numbers=[1,1,3,4,5] ならば $same=[1]
      //ex)$numbers=[1,1,1,2,2] ならば $same=[1,1,1,2]　など、、、
      $same = [];
      for ( $i = 0 ; $i <= 3 ; $i++ ){
        for ( $j = $i+1 ; $j <= 4 ; $j++ ){
          if ( $numbers[$i] == $numbers[$j] ){
            $same[] = $numbers[$i];
          }
        }
      }
      //$sameの配列数に応じて役を決定する。
      switch (count($same)){
        case 1:
          $yaku = "ワンペア";
          break;
        case 2:
          $yaku = "ツーペア";
          break;
        case 3:
          $yaku = "スリーカード";
          break;
        case 4:
          $yaku = "フルハウス";
          break;
        case 6:
          if ( $numbers[0] == 0 ){
            $yaku = "ファイブカード";
          }else{
            $yaku = "フォーカード";
          }
          break;
      }
      //フラッシュの条件設定
      $flash = $suits[0] == $suits[1] &&
               $suits[0] == $suits[2] &&
               $suits[0] == $suits[3] &&
               $suits[0] == $suits[4] ;
      //フラッシュの役決定
      if ( $flash ){
        $yaku = "フラッシュ";
      }

      //ストレートの条件設定
      $straight = $numbers[1] == $numbers[0] + 1 &&
                  $numbers[2] == $numbers[1] + 1 &&
                  $numbers[3] == $numbers[2] + 1 &&
                  $numbers[4] == $numbers[3] + 1 ;
      //ストレートの役決定
      if ( $straight ){
        $yaku = "ストレート";
      }

      // ストレートフラッシュの役決定
      if( $flash && $straight ){
        $yaku = "ストレートフラッシュ";
      }

      //ロイヤルストレートフラッシュの役決定
      $royal = [1,10,11,12,13];
      if ( $flash && $numbers == $royal ){
        $yaku = "ロイヤルストレートフラッシュ";
      }

    }
    print "役は".$yaku;
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ポーカー役判定（ジョーカーあり）</title>
</head>
<body>
    <section>
        <p>手札は</p>
        <p><?php foreach($cards as $card): ?><?=$card['suit'].$card['number'] ?><?php endforeach; ?></p>
        <p><?=judge($cards) ?>です。</p>
    </section>
</body>
</html>

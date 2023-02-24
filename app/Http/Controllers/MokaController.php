<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMokaRequest;
use App\Http\Requests\UpdateMokaRequest;
use App\Models\Moka;
//use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;

use App\Rules\KatakanaRule;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Remise;
use App\Mail\ThanksMail;
use App\Mail\NextengineMail;
use Mail;

class MokaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('moka.index');
    }

    //数量チェック
    public function quantityCheck(Request $request)
    {
        $items = [];
        $input1 = $request->input('input1');
        $input2 = $request->input('input2');
        $input3 = $request->input('input3');
        $input4 = $request->input('input4');
        $input5 = $request->input('input5');
        $input6 = $request->input('input6');

        $sum = intval($input1) + intval($input2) + intval($input3) + intval($input4) + intval($input5) + intval($input6*5);

        if ($input1 >= 1) {
            $items['アマルフィ／AMALFI（10カプセル入り）'] = $input1;
        }
        if ($input2 >= 1) {
            $items["コジモ／COSIMO（10カプセル入り）"] = $input2;
        }
        if ($input3 >= 1) {
            $items["ロッソ／ROSSO（10カプセル入り）"] = $input3;
        }
        if ($input4 >= 1) {
            $items["ヴィオラ／VIOLA（10カプセル入り）"] = $input4;
        }
        if ($input5 >= 1) {
            $items["ヴェルナッツア／VERNAZZA（10カプセル入り）"] = $input5;
        }
        if ($input6 >= 1) {
            $items["5種セット（10カプセル入り×５本）"] = $input6;
        }

        //定価
        $price=600;
        //送料
        $postage=0;

        if( $sum>=5 and $sum <=9 ){
            $price=540;
        }elseif($sum>=10 and $sum <=14){
            $price=480;
        }elseif($sum>=15 and $sum <=19){
            $price=450;
        }elseif($sum>=20){
            $price=400;
        }else{
            $price=600;
            $postage=660;
        }

        $total_price = floor( $sum * $price * 1.08 ) + $postage;
        $pricesum = $sum * $price ;
        //上記計算で小数点がつくので整数に変換
        $total_price = intval($total_price);
        session(['pricesum' => $pricesum]);
        session(['price' => $price]);
        session(['items' => $items]);
        session(['total_price' => $total_price]);
        session(['postage' => $postage]);
        session(['sum' => $sum]);//合計数量
       

        if ($sum < 1) {
            return redirect()->route('moka')->with(['message' => '数量を入力してください'])->withInput();
        } else {
            //1個以上なら住所入力ページへ移動
            return redirect()->route('address');
        }

    }

    //住所入力ページ
    public function address()
    {
        //redirectで動作する際に表示するviewの指定
        return view('moka.address');
    }


    //住所入力のバリデーション
    public function verify(Request $request)
    {
        $rules = [
            'email' => 'required|email|confirmed:email', // emailフィールドは必須チェック、emailの形になっているかチェックを行う
            'email_confirmation' => 'required|email',
            'name' => 'required|max:255',      // nameフィールドは必須チェック、255文字以内かをチェックする
            'kana' => 'required|max:255',     // titleフィールドは任意入力、入力があった時は255文字以内かをチェックする
            'kana' => ['required', new KatakanaRule],
            'tel' => 'required | numeric | digits_between:9,11',
            'postal' => 'required',
            'prefecture' => 'required',
            'city' => 'required',
            'street' => 'required',
            'interval' => 'required',
            'week' => 'required',
            'youbi' => 'required',
            'message' => 'nullable|max:6000', // messageフィールドは任意入力、入力があった時は6000文字以内かをチェックする
        ];

        $messages = [
            'email.required'   => 'メールアドレスを入力してください。',     // emailフィールドで入力がなかった時に表示されるエラーメッセージ
            'email.email'      => '正しいメールアドレスを入力してください。', // emailフィールドで正しいemail形式でなかった時に表示されるエラーメッセージ
            'email.confirmed' => 'メールアドレスが一致しません。',
            'email_confirmation.required' => 'メールアドレスを入力してください。',
            'name.required'    => '名前を入力してください。',              // nameフィールドで入力がなかった時に表示されるエラーメッセージ
            'name.max'         => '名前は:max文字以内で入力してください。', // nameフィールドで255文字を超えた時に表示されるエラーメッセージ
            'kana.required'   => 'カナを入力してください。',
            'kana.katakana'   => 'カナを入力してください。',
            'kana.max'        => 'カナは:max文字以内で入力してください。',  // titleフィールドで255文字を超えた時に表示されるエラーメッセージ
            'tel.required' => '電話番号を入力してください。',
            'tel.numeric' => '数字のみを入力してください。',
            'tel.digits_between' => '9〜11桁で入力してください。',
            'postal.required'      => '郵便番号を入力してください。',
            'prefecture.required'      => '都道府県を入力してください。',
            'city.required'      => '住所1を入力してください。',
            'street.required'      => '住所2を入力してください。',
            'interval.required'      => 'お届け間隔を選択してください。',
            'week.required'      => 'お届け週を選択してください。',
            'youbi.required'      => 'お届け曜日を選択してください。',
            'message.max'      => 'メッセージは:max文字以内で入力してください。', // messageフィールドで6000文字を超えた時に表示されるエラーメッセージ
        ];
        $validator = Validator($request->all(), $rules, $messages);
        $validated = $validator->validate();

        //ここで受注番号(order_number=S_TORIHIKI_NO)を作成
        $order_number=time();
        //ここで受注番号、名前、住所、商品をDBに登録
        $name=$validated['name'];
        $email=$validated['email'];
        $kana=$validated['kana'];
        $tel=$validated['tel'];
        $postal=$validated['postal'];
        $prefecture=$validated['prefecture'];
        $city=$validated['city'];
        $street=$validated['street'];
        $interval=$validated['interval'];
        $week=$validated['week'];
        $youbi=$validated['youbi'];
        $message=$validated['message'];
        $shipping=session()->get('postage');
        $tax=session()->get('total_price') - session()->get('total_price') /1.08;
        $tax=intval($tax);
        $total=session()->get('total_price');
        $subtotal=$total-$tax;
        $sum=session()->get('sum');

        //単価を出す
        if($sum<5){
            $price=600;
        }elseif($sum<10){
            $price=540;
        }elseif($sum<15){
            $price=480;
        }elseif($sum<20){
            $price=450;
        }else{
            $price=400;
        }

        //メインをDBに追加
        $order = order::create([
            'order_number' => $order_number,
            'name' => $name,
            'email' => $email,
            'kana' => $kana,
            'tel' => $tel,
            'postal' => $postal,
            'prefecture' => $prefecture,
            'city' => $city,
            'street' => $street,
            'interval' => $interval,
            'week' => $week,
            'youbi' => $youbi,
            'message' => $message,
            'shipping' => $shipping,
            'tax' =>$tax,
            'total' => $total,
            'sum' => $sum,
            'subtotal' => $subtotal,
        ]);

        //明細をDBに追加
        foreach ( session()->get('items') as $key => $val ){
            //商品番号
            //個数に変換(5種セットは5)
            if(false !== strpos($key,'アマルフィ')){
                $itemNo="AMALFI";
                $count=$val;
            }elseif(false !== strpos($key,'コジモ')){
                $itemNo="COSIMO";
                $count=$val;
            }elseif(false !== strpos($key,'ロッソ')){
                $itemNo="ROSSO";
                $count=$val;
            }elseif(false !== strpos($key,'ヴィオラ')){
                $itemNo="VIOLA";
                $count=$val;
            }elseif(false !== strpos($key,'ヴェルナッツア')){
                $itemNo="VERNAZZA";
                $count=$val;
            }elseif(false !== strpos($key,'5種セット')){
                $itemNo="moka-5assort";
                $count=$val*5;
            }else{
                $itemNo="";
                $count="";           
            }
            //DBに登録する
            orderdetail::create([
                'order_number' => $order_number,
                'item_name' => $key,
                'amount' => $val,
                'item_number'=> $itemNo,
                'count' => $count,
                'price' => $price
            ]);
        }
        // 入力チェック成功時はresources/view/moka/confirm.blade.phpに内容を表示させる
        //return viewなのでルート側の記載はなくても大丈夫
        //エラーがなければ確認画面へ移動
        return view('moka.confirm', compact('validated','order_number'));
    }

    //ルミーズの結果通知を受取り800をルミーズへ戻す
    public function result(Request $request)
    {
        //ルミーズからPOSTされてくる
        $TRANID = $request->input('X-TRANID');
        $TORIHIKI_NO = $request->input('X-S_TORIHIKI_NO');
        $AMOUNT = $request->input('X-AMOUNT');
        $TAX = $request->input('X-TAX');
        $TOTAL = $request->input('X-TOTAL');
        $REFAPPROVED = $request->input('X-REFAPPROVED');
        $TRANID = $request->input('X-TRANID');
        $TORIHIKI_NO = $request->input('X-S_TORIHIKI_NO');
        $AMOUNT = $request->input('X-AMOUNT');
        $TAX = $request->input('X-TAX');
        $TOTAL = $request->input('X-TOTAL');
        $REFAPPROVED = $request->input('X-REFAPPROVED');
        $REFFORWARDED = $request->input('X-REFFORWARDED');
        $ERRCODE = $request->input('X-ERRCODE');
        $ERRINFO = $request->input('X-ERRINFO');
        $ERRLEVEL = $request->input('X-ERRLEVEL');
        $CODE = $request->input('X-R_CODE');
        $TYPE = $request->input('REC_TYPE');
        $REFGATEWAYNO = $request->input('X-REFGATEWAYNO');
        $PAYQUICKID = $request->input('X-PAYQUICKID');
        $PARTOFCARD = $request->input('X-PARTOFCARD');
        $EXPIRE = $request->input('X-EXPIRE');
        $NAME = $request->input('X-NAME');
        $MEMBERID = $request->input('X-AC_MEMBERID');
        $KAIIN_NO = $request->input('X-AC_S_KAIIN_NO');
        $AC_AMOUNT = $request->input('X-AC_AMOUNT');
        $AC_TOTAL = $request->input('X-AC_TOTAL');
        $YYYYMMDD = $request->input('YYYYMMDD');
        $AC_INTERVAL = $request->input('X-AC_INTERVAL');
        $CARDBRAND = $request->input('X-CARDBRAND');

        //ルミーズの結果をDBに保存
        $order = remise::create([
            'X-TRANID' => $TRANID,
            'X-S_TORIHIKI_NO' => $TORIHIKI_NO,
            'X-AMOUNT' =>$AMOUNT,
            'X-TAX' => $TAX,
            'X-TOTAL' => $TOTAL,
            'X-REFAPPROVED' => $REFAPPROVED,
            'X-REFFORWARDED' => $REFFORWARDED,
            'X-ERRCODE' => $ERRCODE,
            'X-ERRINFO' => $ERRINFO,
            'X-ERRLEVEL' => $ERRLEVEL,
            'X-R_CODE' => $CODE,
            'REC_TYPE' => $TYPE,
            'X-REFGATEWAYNO' => $REFGATEWAYNO,
            'X-PAYQUICKID' => $PAYQUICKID,
            'X-PARTOFCARD' => $PARTOFCARD,
            'X-EXPIRE' => $EXPIRE,
            'X-NAME' => $NAME,
            'X-AC_MEMBERID' => $MEMBERID,
            'X-AC_S_KAIIN_NO' => $KAIIN_NO,
            'X-AC_AMOUNT' => $AC_AMOUNT,
            'X-AC_TOTAL' => $AC_TOTAL,
            'YYYYMMDD' => $YYYYMMDD,
            'X-AC_INTERVAL' => $AC_INTERVAL,
            'X-CARDBRAND' => $CARDBRAND
        ]);

        return view('moka.result');
    }
    //ルミーズからOKが返ってきた場合
    public function thanks( Request $request )
    {
        $id=$request->id;
        //顧客とネクストエンジンにメールを出す
        $customer=Order::where( 'order_number', $id )->first();
        $email=$customer->email;
        $name=$customer->name;
        $kana=$customer->kana;
        $tel=$customer->tel;
        $postal=$customer->postal;
        $prefecture=$customer->prefecture;
        $city=$customer->city;
        $street=$customer->street;
        $interval=$customer->interval;
        $week=$customer->week;
        $youbi=$customer->youbi;
        $message=$customer->message;
        $subtotal=$customer->subtotal;//小計
        $shipping=$customer->shipping;//送料
        $tax=$customer->tax;//税
        $total=$customer->total;//合計
        $detail=OrderDetail::where( 'order_number', $id )->get();


        //お客様にメール
        $content=$name."様\n\n定期購入のお申し込みありがとうございます。モカプレッソです。\n";
        $content.="下記の通りご注文を承りました。\n\n";
        $content.="■ご注文番号\n";
        $content.=$id."\n\n";
        $content.="■お名前\n";
        $content.=$name."\n\n";
        $content.="■郵便番号\n";
        $content.='〒'.$postal."\n";
        $content.="■ご住所\n";
        $content.=$prefecture.$city.$street."\n";
        $content.="■お電話\n";
        $content.=$tel."\n\n";
        $content.="■2回目以降のお届け間隔\n";
        $content.=$interval.' '.$week.' '.$youbi."\n\n";
        $content.="■お届け商品\n";
        foreach($detail as $d){
            $content.=$d["item_name"]." × ";
            $content.=$d["amount"];
            $content.="\n";
        }
        $content.="■小計金額\n";
        $content.=$subtotal."\n";
        $content.="■送料\n";
        $content.=$shipping."\n";
        $content.="■消費税\n";
        $content.=$tax."\n";
        $content.="■合計金額\n";
        $content.=$total."\n";

        $content.="\n";
        $content.="第一回目の発送はご注文日の翌営業日となります。\n";
        $content.="ご不明な点などございましたらお気軽にお問い合わせください。\n";
        $content.="今後ともよろしくお願い申し上げます。\n\n";
        $content.="----------------------------------------------------\n";
        $content.="株式会社ディーキャスト\n";
        $content.="〒116-0001 東京都荒川区町屋7-13-12\n";
        $content.="Phone：080-2233-7776\n";
        $content.="Fax：03-6240-8066\n";
        $content.="Email：info@mokapresso.jp\n";
        $content.="----------------------------------------------------\n";

        $to =$email;
        $bcc="info@mokapresso.jp";
	    Mail::to($to)->bcc($bcc)->send(new ThanksMail($content));

        //ネクストエンジンにメール
        $nextmail=
        "注文コード：".$id."\n".
        "注文日時：".date('Y年m月d日 H時i分s秒')."\n".
        "■注文者の情報\n".
        "氏名：".$name."\n".
        "氏名（フリガナ）：".$kana."\n".
        "郵便番号：".$postal."\n".
        "住所：".$prefecture.$street.$city."\n".
        "電話番号：".$tel."\n".
        "Ｅメールアドレス：".$email."\n".
        "■支払方法\n".
        "支払方法："."クレジットカード"."\n".
        "■注文内容\n".
        "------------------------------------------------------------\n";

        foreach($detail as $d){
            $itemName=$d["item_name"];
            $count=$d["count"];
            $amount=$d["amount"];
            $tanka=$d['price'];
            if($d['item_name']=="moka-5assort"){
                $tanka=$tanka*5;
            }

            $nextmail.="商品番号：\n".
            "注文商品名：".$itemName."\n".
            "商品オプション："."\n".
            "単価：￥".number_format($tanka)."\n".
            "数量：".$amount."\n".
            "小計：￥".number_format($amount*$tanka)."\n".
            "------------------------------------------------------------\n";

        }

        $nextmail.="商品合計：￥".number_format($subtotal)."\n".
        "税金：".number_format($tax)."\n".
        "送料：".number_format($shipping)."\n".
        "手数料："."\n".
        "その他費用："."\n".
        "ポイント利用額："."\n".
        "------------------------------------------------------------\n".
        "合計金額(税込)：￥".number_format($total)."\n".
        "------------------------------------------------------------\n".
        "■届け先の情報"."\n".
        "[送付先1]"."\n".
        "送付先1氏名：".$name."\n".
        "送付先1氏名（フリガナ）：".$kana."\n".
        "送付先1郵便番号：".$postal."\n".
        "送付先1住所：".$prefecture.$street.$city."\n".
        "送付先1電話番号：".$tel."\n".
        "送付先1のし・ギフト包装：\n".
        "送付先1お届け方法：宅配便\n".
        "送付先1お届け希望日：\n".
        "送付先1お届け希望時間：\n".
        "■通信欄:".$interval.' '.$week.' '.$youbi."\n";

        $to = $email;
        //$bcc="info@mokapresso.jp";
        $bcc = "segawa@lookingfor.jp";
        $content = $nextmail;
	    Mail::to($to)->bcc($bcc)->send(new NextengineMail($content));

        return view('moka.thanks',compact('name'));
    }
    //ルミーズからNGが返ってきた場合
    public function ng(Request $request)
    {
        $id=$request->id;
        return view('moka.ng');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMokaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMokaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Moka  $moka
     * @return \Illuminate\Http\Response
     */
    public function show(Moka $moka)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Moka  $moka
     * @return \Illuminate\Http\Response
     */
    public function edit(Moka $moka)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMokaRequest  $request
     * @param  \App\Models\Moka  $moka
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMokaRequest $request, Moka $moka)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Moka  $moka
     * @return \Illuminate\Http\Response
     */
    public function destroy(Moka $moka)
    {
        //
    }
}

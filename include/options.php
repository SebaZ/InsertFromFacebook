<h1>Insert From Facebook</h1>
<p>
Podaj identyfikatory profili lub fanpage Facebooka, każdy w nowej linii.
</p>
<form method="post">
    <label for="fbids">Identyfikatory FB</label><br />
    <textarea name="fbids" id="fbids" rows="10" cols="40"><?php echo $value; ?></textarea>
    <br /><i style="color: #666; font-size: 11px;">Np. 205748262962024 dla Kulczyk Foundation</i>
    <div class="cff-tooltip cff-more-info">
        <ul>
            <li>W celu poznania ID użyj <a href="http://lookup-id.com/" target="_blank" title="Find my ID">tego narzędzia</a> w celu znalezienia poprawnego identyfikatora.</li>
            <li>Weź pod uwagę, że każdy profil ma swój numeryczny identyfikator i w przypadku, np. <code>https://www.facebook.com/kulczykfoundation</code> będzie to numer <b>205748262962024</b>, który poda narzędzie <a href="http://lookup-id.com/" target="_blank" title="Find my ID">Find my ID</a>.</li>
        </ul>
    </div>
    <input type="submit" value="Zapisz" class="button button-primary button-large">
</form>
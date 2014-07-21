
<div class="tlo">

    <div class="container">
        <div class="przyciski">
            <a class="btn btn-lg btn-default" href="/www/page/download/gsk-installer-2.28.exe" role="button">Pobierz najnowszą
                wersję programu</a>
            <a id="btn-instrukcja" class="btn btn-lg btn-default" href="javascript:void(0)" role="button">Instrukcja
                pobierania i instalacji</a>

            <div class="instrukcja roundedcorners">
                <h4>Instrukcja instalacji</h4>

                <p>Proszę wykonać następujące kroki:</p>
                <ol class="lista">
                    <li>Pobrać najnowszą wersję programu (przycisk powyżej).</li>
                    <li>Uruchomić pobrany plik exe.</li>
                    <li>Jeżeli pojawi się takie okno:
                        <a class="fancybox thumbnail" href="/www/page/img/instrukcja/windows_protected_your_pc.jpg" title="">
                            <img src="/www/page/img/instrukcja/windows_protected_your_pc_s.jpg" alt=""/>
                        </a>
                        należy nacisnąć przycisk "More info", a następnie przycisk "Run Anyway"
                        <a class="fancybox thumbnail" href="/www/page/img/instrukcja/windows_run_anyway.jpg" title="">
                            <img src="/www/page/img/instrukcja/windows_run_anyway_s.jpg" alt=""/>
                        </a>
                    </li>
                    <li>Po pojawieniu się okna instalatora należy wcisnąć przycisk "install"
                        <a class="fancybox thumbnail" href="/www/page/img/instrukcja/instalator_1.png" title="">
                            <img src="/www/page/img/instrukcja/instalator_1_s.png" alt=""/>
                        </a>
                        a po zakończeniu instalacji przycisk "close"
                        <a class="fancybox thumbnail" href="/www/page/img/instrukcja/instalator_2.png" title="">
                            <img src="/www/page/img/instrukcja/instalator_2_s.png" alt=""/>
                        </a>
                    </li>
                    <li>Odnośnik do programu pojawi się w Menu Start pod nazwą "ProductViewer" w folderze "GSK".</li>
                </ol>
            </div>

            <a id="btn-kontakt" class="btn btn-lg btn-default" href="javascript:void(0)" role="button">Kontakt (Łukasz Parada)</a>

            <div class="kontakt roundedcorners">
                <h4>Kontakt</h4>
                <p>W przypadku problemów z instalacją proszę pisać na adres <strong>lukasz.n.parada@gsk.com</strong></p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    {literal}
    $(document).ready(function () {
        $(".fancybox").fancybox();

        $("#btn-kontakt").click(function(){
            $("div.kontakt").slideToggle();
        });
        $("#btn-instrukcja").click(function(){
            $("div.instrukcja").slideToggle();
        });
    });
    {/literal}
</script>

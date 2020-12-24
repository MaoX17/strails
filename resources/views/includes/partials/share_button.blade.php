<div class="social-buttons">
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank">
                           <i class="fa fa-facebook-official"></i>
                        </a>
    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}" target="_blank">
                            <i class="fa fa-twitter-square"></i>
                        </a>
    <a href="https://plus.google.com/share?url={{ urlencode(url()->current()) }}" target="_blank">
                           <i class="fa fa-google-plus-square"></i>
                        </a>
    <a href={{ "https://wa.me/?text=".(urlencode(url()->current())) }} target="_blank">
                            <i class="fa fa-whatsapp"></i>
                        </a>

</div>

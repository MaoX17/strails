<div class="social-buttons">
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank">
                           <i class="fab fa-facebook"></i>
                        </a>
    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
    <a href="https://plus.google.com/share?url={{ urlencode(url()->current()) }}" target="_blank">
                           <i class="fab fa-google-plus"></i>
                        </a>
    <a href={{ "https://wa.me/?text=".(urlencode(url()->current())) }} target="_blank">
                            <i class="fab fa-whatsapp"></i>
                        </a>

</div>

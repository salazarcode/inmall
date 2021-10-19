<div class="container item">
  @set($product, wc_get_product())
  <div class="is-relative has-margin-bottom-20 has-shadow-overlay is-clipped has-min-height-300 is-rounded"
    style="background:url(@thumbnail('full', false)) center center / cover no-repeat;">
  </div>
  @hasfield('video')
  {{-- Video stuff --}}
  @endfield

  <div class="title is-4 has-text-primary">@title</div>
  <p class="has-text-dark">@excerpt</p>
  <div class="buttons has-margin-top-30">
    <div class="button is-light has-text-dark">
      <span class="icon"><svg id="Heart" xmlns="http://www.w3.org/2000/svg" width="39" height="42" viewBox="0 0 39 42">
          <rect id="Rectángulo_220" data-name="Rectángulo 220" width="39" height="42" fill="none" />
          <g id="Heart-2" data-name="Heart" transform="translate(4.751 7.127)">
            <path id="Trazado_253" data-name="Trazado 253"
              d="M259.66,223.033a8.566,8.566,0,0,0-5.711-3.865,8.307,8.307,0,0,0-6.608,1.691,1.424,1.424,0,0,1-1.726,0,8.307,8.307,0,0,0-6.608-1.691,8.566,8.566,0,0,0-5.711,3.865,10.932,10.932,0,0,0,.207,11.612c2.847,4.626,8.262,12.152,12.983,12.152s10.121-7.556,12.968-12.152a10.932,10.932,0,0,0,.207-11.612Z"
              transform="translate(-231.727 -219.042)" fill="#ffdd50" opacity="0.1" />
            <path id="Trazado_254" data-name="Trazado 254"
              d="M259.66,223.033a8.566,8.566,0,0,0-5.711-3.865,8.307,8.307,0,0,0-6.608,1.691,1.424,1.424,0,0,1-1.726,0,8.307,8.307,0,0,0-6.608-1.691,8.566,8.566,0,0,0-5.711,3.865,10.932,10.932,0,0,0,.207,11.612c2.847,4.626,8.262,12.152,12.983,12.152s10.121-7.556,12.968-12.152a10.932,10.932,0,0,0,.207-11.612Z"
              transform="translate(-231.727 -219.042)" fill="none" stroke="#ffdd50" stroke-linecap="round"
              stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3" />
          </g>
        </svg>
      </span>
      <span>Guardar</span>
    </div>
    <div class="button is-light has-text-dark">
      <span class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33">
          <g id="Profile" transform="translate(-0.575 -0.575)">
            <rect id="Rectángulo_250" data-name="Rectángulo 250" width="33" height="33" transform="translate(0.575 0.575)" fill="none"/>
            <path id="Trazado_276" data-name="Trazado 276" d="M248.624,183.13c-1.419-1.661-3.109-3.08-4.982-3.08a7.1,7.1,0,0,0-5,3.08,6.43,6.43,0,0,0,.426,8.659h0a6.444,6.444,0,0,0,9.127,0h0a6.43,6.43,0,0,0,.426-8.659Z" transform="translate(-226.563 -177.165)" fill="none" stroke="#ffdd50" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"/>
            <rect id="Rectángulo_211" data-name="Rectángulo 211" width="28.389" height="9.51" rx="3.35" transform="translate(2.885 21.763)" fill="none" stroke="#ffdd50" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"/>
            <g id="Grupo_10" data-name="Grupo 10" transform="translate(2.885 2.885)" opacity="0.1">
              <path id="Trazado_277" data-name="Trazado 277" d="M248.624,183.13c-1.419-1.661-3.109-3.08-4.982-3.08a7.1,7.1,0,0,0-5,3.08,6.43,6.43,0,0,0,.426,8.659h0a6.444,6.444,0,0,0,9.127,0h0a6.43,6.43,0,0,0,.426-8.659Z" transform="translate(-229.447 -180.05)" fill="#ffdd50"/>
              <rect id="Rectángulo_212" data-name="Rectángulo 212" width="28.389" height="9.51" rx="3.35" transform="translate(0 18.879)" fill="#ffdd50"/>
            </g>
          </g>
        </svg>
        
      </span>
      <span>{{get_post_meta(get_the_ID(),'total_sales', true)}}</span>
    </div>
  </div>
  <div class="prices">
    <span class="price-current is-size-4 has-text-weight-bold has-text-success">${{$product->get_sale_price()}}</span>
    <del class="price-current is-size-4 has-text-weight-light has-text-dark">${{$product->get_regular_price()}}</del>
  </div>
  <div class="buttons has-margin-top-30">
    <a href="@permalink" class="button is-large is-primary is-outlined">
      <span>Inscríbete ahora</span>
      <span class="icon">
        <svg id="Bag" xmlns="http://www.w3.org/2000/svg" width="57" height="57" viewBox="0 0 57 57">
          <rect id="Rectángulo_219" data-name="Rectángulo 219" width="57" height="57" fill="none"/>
          <path id="Trazado_225" data-name="Trazado 225" d="M253.428,247.145h-12.4a9.3,9.3,0,0,1-9.131-10.991l1.349-7.193a9.3,9.3,0,0,1,9.115-7.581h9.751a9.3,9.3,0,0,1,9.115,7.581l1.349,7.193a9.3,9.3,0,0,1-9.146,10.991Z" transform="translate(-218.74 -200.195)" fill="#6935f0" opacity="0.1"/>
          <path id="Trazado_226" data-name="Trazado 226" d="M253.428,247.145h-12.4a9.3,9.3,0,0,1-9.131-10.991l1.349-7.193a9.3,9.3,0,0,1,9.115-7.581h9.751a9.3,9.3,0,0,1,9.115,7.581l1.349,7.193a9.3,9.3,0,0,1-9.146,10.991Z" transform="translate(-218.74 -200.195)" fill="none" stroke="#6935f0" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3"/>
          <line id="Línea_1" data-name="Línea 1" x1="8.433" transform="translate(24.27 38.765)" fill="none" stroke="#6935f0" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3"/>
          <line id="Línea_2" data-name="Línea 2" x1="1.953" y1="8.759" transform="translate(32.703 15.992)" fill="none" stroke="#6935f0" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3"/>
          <line id="Línea_3" data-name="Línea 3" y1="8.759" x2="1.953" transform="translate(22.317 15.992)" fill="none" stroke="#6935f0" stroke-linecap="round" stroke-miterlimit="10" stroke-width="3"/>
        </svg>
      </span>
    </a>
  </div>
</div>

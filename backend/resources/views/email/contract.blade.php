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
                                <th
                                  class="column"
                                  style="
                                    font-size: 0pt;
                                    line-height: 0pt;
                                    padding: 0;
                                    margin: 0;
                                    font-weight: normal;
                                  "
                                >
                                  <table
                                    width="100%"
                                    border="0"
                                    cellspacing="0"
                                    cellpadding="0"
                                  >
                                    <tr>
                                      <td align="right">
                                        <table
                                          class="center"
                                          border="0"
                                          cellspacing="0"
                                          cellpadding="0"
                                          style="text-align: center;"
                                        >
                                          <tr>
                                            <th
                                              class="column"
                                              style="
                                                font-size: 0pt;
                                                line-height: 0pt;
                                                padding: 0;
                                                margin: 0;
                                                font-weight: normal;
                                              "
                                            >
                                              <table
                                                width="100%"
                                                border="0"
                                                cellspacing="0"
                                                cellpadding="0"
                                              >
                                                <tr>
                                                  <td
                                                    class="text-header m-center right"
                                                    style="
                                                      color: #737373;
                                                      font-family: Arial,
                                                        sans-serif;
                                                      font-size: 12px;
                                                      line-height: 16px;
                                                      text-align: right;
                                                    "
                                                  >
                                                    <multiline>
													 ¿No puede visualizar las imágenes?
                                                      <webversion
                                                        class="link-grey-u"
                                                        style="
                                                          color: #737373;
                                                          text-decoration: underline;
                                                        "
                                                      >
                                                        Abrir con el navegador
                                                      </webversion>
                                                    </multiline>
                                                  </td>
                                                </tr>
                                              </table>
                                            </th>
                                            <th
                                              class="column-empty2"
                                              width="20"
                                              style="
                                                font-size: 0pt;
                                                line-height: 0pt;
                                                padding: 0;
                                                margin: 0;
                                                font-weight: normal;
                                              "
                                            ></th>
                                            <th
                                              class="column"
                                              style="
                                                font-size: 0pt;
                                                line-height: 0pt;
                                                padding: 0;
                                                margin: 0;
                                                font-weight: normal;
                                              "
                                            >
                                              <table
                                                width="100%"
                                                border="0"
                                                cellspacing="0"
                                                cellpadding="0"
                                              >
                                                <tr>
                                                  <td align="right">
                                                    <table
                                                      class="center"
                                                      border="0"
                                                      cellspacing="0"
                                                      cellpadding="0"
                                                      style="
                                                        text-align: center;
                                                      "
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
                                                                TERMINOS Y CONDICIONES
                                                              </span>
                                                            </a>
                                                          </multiline>
                                                        </td>
                                                      </tr>
                                                    </table>
                                                  </td>
                                                </tr>
                                              </table>
                                            </th>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </th>
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
                        </layout>
                        <!-- END Article / Image + Title + Text -->

                        <!-- Three Columns -->
                        <layout label="Three Columns">
                          <table
                            width="100%"
                            border="0"
                            cellspacing="0"
                            cellpadding="0"
                            bgcolor="#ffffff"
                          >
                            <tr>
                              <td
                                class="p30-15"
                                style="padding: 50px 40px;"
                                align="center"
                              >
                                <table
                                  width="100%"
                                  border="0"
                                  cellspacing="0"
                                  cellpadding="0"
                                >
                                  <tr>
                                    <td
                                      align="center"
                                      style="padding-bottom: 40px;"
                                    >
                                      <table
                                        border="0"
                                        cellspacing="0"
                                        cellpadding="0"
                                        class="mobile-shell"
                                      >
                                        <tr>
                                          <td
                                            class="m-td"
                                            width="50"
                                            style="
                                              font-size: 0pt;
                                              line-height: 0pt;
                                              text-align: left;
                                            "
                                          >
                                            <img
                                              src="{{ asset('img/t1_free_ribbon_l.jpg') }}"
                                              width="50"
                                              height="55"
                                              editable="true"
                                              border="0"
                                              alt=""
                                            />
                                          </td>
                                          <td valign="top">
                                            <table
                                              width="100%"
                                              border="0"
                                              cellspacing="0"
                                              cellpadding="0"
                                            >
                                              <tr>
                                                <td
                                                  height="45"
                                                  bgcolor="#72b314"
                                                  class="h3 ribbon white center"
                                                  style="
                                                    font-family: 'Playfair Display',
                                                      Georgia, serif;
                                                    font-size: 22px;
                                                    line-height: 32px;
                                                    font-weight: bold;
                                                    color: #ffffff;
                                                    text-align: center;
                                                  "
                                                >
                                                  <multiline>
                                                    Detalle de la Reservación
                                                  </multiline>
                                                </td>
                                              </tr>
                                            </table>
                                          </td>
                                          <td
                                            class="m-td"
                                            width="50"
                                            style="
                                              font-size: 0pt;
                                              line-height: 0pt;
                                              text-align: left;
                                            "
                                          >
                                            <img
                                              src="{{ asset('img/t1_free_ribbon_r.jpg') }}"
                                              width="50"
                                              height="55"
                                              editable="true"
                                              border="0"
                                              alt=""
                                            />
                                          </td>
                                        </tr>
                                      </table>
                                    </td>
                                  </tr>
                                  <!-- DESCRIPTION ROOM -->
                                  <tr>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      @foreach ($reservation->detail as $key => $item)
                                        <!-- DESCRIPTION ROOM NAME DESCRIPTION -->
                                        <tr>
                                            <td
                                                class="p30-15"
                                                style="padding: 0px 0px 10px 0px;"
                                            >
                                                <table
                                                width="100%"
                                                border="0"
                                                cellspacing="0"
                                                cellpadding="0"
                                                >
                                                  <tr>
                                                      <td
                                                      class="text center white"
                                                      style="
                                                          font-family: Arial, sans-serif;
                                                          font-size: 14px;
                                                          line-height: 26px;
                                                          text-align: center;
                                                          color: #020202;
                                                      "
                                                      >
                                                      <multiline>
                                                        <h1># {{ $item->room->number }}</h1>
                                                      </multiline>
                                                      </td>
                                                  </tr>
                                                  <tr>
                                                      <td
                                                      class="h2 white center pb15"
                                                      style="
                                                          font-family: 'Playfair Display', Georgia,
                                                          serif;
                                                          font-size: 30px;
                                                          line-height: 46px;
                                                          font-weight: bold;
                                                          color: #E1B24C;
                                                          text-align: center;
                                                          padding-bottom: 15px;
                                                      "
                                                      >
                                                      <multiline>
                                                          {{ $item->room->name }}
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
                                                          color: #020202;
                                                      "
                                                      >
                                                      <multiline>
                                                          {!! $item->room->description !!}
                                                      </multiline>
                                                      </td>
                                                  </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <!-- FIN DESCRIPTION ROOM NAME DESCRIPTION -->
                                        <tr>
                                            <td align="center" class="p30-15" style="padding: 0px 0px 10px 0px;">
                                                <table width="650" border="0" cellspacing="0" cellpadding="0" class="mobile-shell">
                                                    <tr>
                                                        <td class="td" style="width:650px; min-width:650px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <th class="column" width="310" bgcolor="#F4F4F4" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="box" style="padding:45px 30px; border:1px solid #5a5a5a;">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td class="img" width="75" style="font-size:0pt; line-height:0pt; text-align:left;"><img src="{{ asset('img/cama_peque.svg') }}" width="54" height="48" mc:edit="image_20" style="max-width:54px;" border="0" alt="" /></td>
                                                                                            <td class="h5-4 left" style="color:#020202; font-family:'Playfair Display', Georgia, serif; font-size:20px; line-height:18px; text-transform:uppercase; text-align:left;">
                                                                                                <div mc:edit="text_32">
                                                                                                    <strong>Tipo de Cama</strong>
                                                                                                    <br />
                                                                                                    <span class="yellow" style="color:#d1a24c;">{{ $item->room->type_bed->name }}</span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                    <th class="column-empty" width="30" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;"></th>
                                                                    <th class="column" width="310" bgcolor="#F4F4F4" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="box" style="padding:45px 30px; border:1px solid #5a5a5a;">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td class="img" width="90" style="font-size:0pt; line-height:0pt; text-align:left;"><img src="{{ asset('img/habitacion.svg') }}" width="70" height="48" mc:edit="image_19" style="max-width:70px;" border="0" alt="" /></td>
                                                                                            <td class="h5-4 left" style="color:#020202; font-family:'Playfair Display', Georgia, serif; font-size:20px; line-height:18px; text-transform:uppercase; text-align:left;">
                                                                                                <div mc:edit="text_31">
                                                                                                    <strong>Tipo de Habitación</strong>
                                                                                                    <br />
                                                                                                    <span class="yellow" style="color:#d1a24c;">{{ $item->room->type_room->name }}</span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>	
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="p30-15" style="padding: 0px 0px 10px 0px;">
                                                <table width="650" border="0" cellspacing="0" cellpadding="0" class="mobile-shell">
                                                    <tr>
                                                        <td class="td" style="width:650px; min-width:650px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <th class="column" width="310" bgcolor="#F4F4F4" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="box" style="padding:45px 30px; border:1px solid #5a5a5a;">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td class="img" width="75" style="font-size:0pt; line-height:0pt; text-align:left;"><img src="{{ asset('img/cama_adulto.svg') }}" width="54" height="48" mc:edit="image_20" style="max-width:54px;" border="0" alt="" /></td>
                                                                                            <td class="h5-4 left" style="color:#020202; font-family:'Playfair Display', Georgia, serif; font-size:26px; line-height:22px; text-transform:uppercase; text-align:left;">
                                                                                                <div mc:edit="text_32">
                                                                                                    <strong>Camas</strong>
                                                                                                    <br />
                                                                                                    <span class="yellow" style="color:#d1a24c;">{{ $item->room->amount_bed }}</span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                    <th class="column-empty" width="30" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;"></th>
                                                                    <th class="column" width="310" bgcolor="#F4F4F4" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="box" style="padding:45px 30px; border:1px solid #5a5a5a;">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td class="img" width="90" style="font-size:0pt; line-height:0pt; text-align:left;"><img src="{{ asset('img/personas.svg') }}" width="70" height="48" mc:edit="image_19" style="max-width:70px;" border="0" alt="" /></td>
                                                                                            <td class="h5-4 left" style="color:#020202; font-family:'Playfair Display', Georgia, serif; font-size:26px; line-height:22px; text-transform:uppercase; text-align:left;">
                                                                                                <div mc:edit="text_31">
                                                                                                    <strong>Personas</strong>
                                                                                                    <br />
                                                                                                    <span class="yellow" style="color:#d1a24c;">{{ $item->room->amount_people }}</span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>	
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="p30-15" style="padding: 0px 0px 10px 0px;">
                                                <table width="650" border="0" cellspacing="0" cellpadding="0" class="mobile-shell">
                                                    <tr>
                                                        <td class="td" style="width:650px; min-width:650px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <th class="column" width="310" bgcolor="#F4F4F4" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="box" style="padding:45px 30px; border:1px solid #5a5a5a;">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td class="img" width="75" style="font-size:0pt; line-height:0pt; text-align:left;"><img src="{{ asset('img/adulto.svg') }}" width="54" height="48" mc:edit="image_20" style="max-width:54px;" border="0" alt="" /></td>
                                                                                            <td class="h5-4 left" style="color:#020202; font-family:'Playfair Display', Georgia, serif; font-size:20px; line-height:22px; text-transform:uppercase; text-align:left;">
                                                                                                <div mc:edit="text_32">
                                                                                                    <strong>Cantidad de Adultos</strong>
                                                                                                    <br />
                                                                                                    <span class="yellow" style="color:#d1a24c;">{{ $item->room->number_adults }}</span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                    <th class="column-empty" width="30" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;"></th>
                                                                    <th class="column" width="310" bgcolor="#F4F4F4" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="box" style="padding:45px 30px; border:1px solid #5a5a5a;">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td class="img" width="90" style="font-size:0pt; line-height:0pt; text-align:left;"><img src="{{ asset('img/ninos.svg') }}" width="70" height="48" mc:edit="image_19" style="max-width:70px;" border="0" alt="" /></td>
                                                                                            <td class="h5-4 left" style="color:#020202; font-family:'Playfair Display', Georgia, serif; font-size:20px; line-height:22px; text-transform:uppercase; text-align:left;">
                                                                                                <div mc:edit="text_31">
                                                                                                    <strong>Cantidad de Niños</strong>
                                                                                                    <br />
                                                                                                    <span class="yellow" style="color:#d1a24c;">{{ $item->room->number_children }}</span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>	
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="p30-15" style="padding: 0px 0px 10px 0px;">
                                                <table width="650" border="0" cellspacing="0" cellpadding="0" class="mobile-shell">
                                                    <tr>
                                                        <td class="td" style="width:650px; min-width:650px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <th class="column" width="310" bgcolor="#F4F4F4" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="box" style="padding:45px 30px; border:1px solid #5a5a5a;">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td class="img" width="75" style="font-size:0pt; line-height:0pt; text-align:left;"><img src="{{ asset('img/mascota.svg') }}" width="54" height="48" mc:edit="image_20" style="max-width:54px;" border="0" alt="" /></td>
                                                                                            <td class="h5-4 left" style="color:#020202; font-family:'Playfair Display', Georgia, serif; font-size:20px; line-height:18px; text-transform:uppercase; text-align:left;">
                                                                                                <div mc:edit="text_32">
                                                                                                    <strong>Mascotas</strong>
                                                                                                    <br />
                                                                                                    <span class="yellow" style="color:#d1a24c;">{{ $item->room->pets ? 'SI' : 'NO' }}</span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                    <th class="column-empty" width="30" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;"></th>
                                                                    <th class="column" width="310" bgcolor="#F4F4F4" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
                                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td class="box" style="padding:45px 30px; border:1px solid #5a5a5a;">
                                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                        <tr>
                                                                                            <td class="img" width="90" style="font-size:0pt; line-height:0pt; text-align:left;"><img src="{{ asset('img/cantidad_mascota.svg') }}" width="70" height="48" mc:edit="image_19" style="max-width:70px;" border="0" alt="" /></td>
                                                                                            <td class="h5-4 left" style="color:#020202; font-family:'Playfair Display', Georgia, serif; font-size:20px; line-height:18px; text-transform:uppercase; text-align:left;">
                                                                                                <div mc:edit="text_31">
                                                                                                    <strong>Cantidad de Mascotas</strong>
                                                                                                    <br />
                                                                                                    <span class="yellow" style="color:#d1a24c;">{{ $item->room->amount_pets }}</span>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>	
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
                                                color: #020202;
                                            "
                                            >
                                            <multiline>
                                                <h1>Fotografías</h1>
                                            </multiline>
                                            </td>
                                        </tr>
                                        @foreach ($item->room->pictures as $foto)
                                        <!-- FOTOGRAFIAS -->
                                          <tr>
                                            <td>
                                              <table
                                                width="100%"
                                                border="0"
                                                cellspacing="0"
                                                cellpadding="0"
                                              >
                                                <tr>
                                                  <th
                                                    class="column-top"
                                                    width="350"
                                                    style="
                                                      font-size: 0pt;
                                                      line-height: 0pt;
                                                      padding: 5px;
                                                      margin: 0;
                                                      font-weight: normal;
                                                      vertical-align: top;
                                                    "
                                                  >
                                                    <img
                                                      src="{{ Storage::disk('room')->url("pictures_rooms/$foto->photo") }}"
                                                      width="350"
                                                      height="235"
                                                      editable="true"
                                                      border="1"
                                                      alt="{{ $foto->id }}"
                                                    />
                                                    <br>
                                                  </th>
                                                </tr>
                                              </table>
                                            </td>
                                          </tr>
                                        @endforeach
                                        @if (count($reservation->detail) != $key+1)
                                        <tr>
                                            <td align="center" class="p30-15" style="padding: 20px 10px;"><hr></td>
                                        </tr>
                                        @endif
                                      @endforeach
                                    </table>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>                        
                        </layout>
                        <!-- END Three Columns -->

                        <!-- Detail Reservation -->
                        <layout label="Detail Reservation">
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
                                  src="{{ asset('img/zik_zak.png') }}"
                                  width="750"
                                  height="18"
                                  editable="true"
                                  border="0"
                                  alt=""
                                />
                              </td>
                            </tr>
                            <tr>
                              <td
                                class="p30-15"
                                bgcolor="#E6BF68"
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
                                        La información de las habitaciones corresponde a las reservadas.
                                      </multiline>
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
                        </layout>
                        <!-- END Article / Image + Title + Text -->
                        
                        <!-- Two Columns -->
                        <layout label="Two Columns">
                          <table
                            width="100%"
                            border="0"
                            cellspacing="0"
                            cellpadding="0"
                            bgcolor="#f4f4f4"
                          >
                            @foreach ($reservation->detail as $item)
                              <tr>
                                <th>
                                  <table>
                                    <tr>
                                      <td
                                        style="
                                          font-family: 'Playfair Display', Georgia,
                                            serif;
                                          font-size: 18px;
                                          line-height: 25px;
                                          font-weight: bold;
                                          color: #0e0d0d;
                                          text-align: left;
                                          padding: 14px 40px;
                                        "
                                      >
                                        <multiline>
                                          {{ $item->description }}
                                        </multiline>
                                      </td>
                                      <td
                                        style="
                                          font-family: 'Playfair Display', Georgia,
                                            serif;
                                          font-size: 24px;
                                          line-height: 25px;
                                          font-weight: bold;
                                          color: #0e0d0d;
                                          text-align: right;
                                          padding: 14px 20px;
                                        "
                                      >
                                        <multiline>
                                        *  {{ $item->accommodation }}
                                        </multiline>
                                      </td>
                                      <td
                                        style="
                                          font-family: 'Playfair Display', Georgia,
                                            serif;
                                          font-size: 18px;
                                          line-height: 25px;
                                          font-weight: bold;
                                          color: #0e0d0d;
                                          text-align: right;
                                          padding: 14px 20px;
                                        "
                                      >
                                        <multiline>
                                          {{ $reservation->coin->symbol." ".number_format($item->sub, '2', '.', ',') }}
                                        </multiline>
                                      </td>
                                    </tr>
                                  </table>
                                </th>
                              </tr>
                            @endforeach
                            <tr>
                              <td
                                style="
                                  font-family: 'Playfair Display', Georgia,
                                    serif;
                                  font-size: 36px;
                                  line-height: 25px;
                                  font-weight: bold;
                                  color: #0e0d0d;
                                  text-align: center;
                                  padding: 14px 40px;
                                "
                              >
                                <multiline>
                                  Total {{ $reservation->coin->symbol." ".number_format($reservation->total_reservation, '2', '.', ',') }}
                                </multiline>
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
                                  src="{{ asset('img/t1_free_zig_zag_image2.jpg') }}"
                                  width="750"
                                  height="9"
                                  editable="true"
                                  border="0"
                                  alt=""
                                />
                              </td>
                            </tr>
                          </table>
                        </layout>
                        <!-- END Two Columns -->

                        <!-- Title + Copy -->
                        <layout label="Title + Copy">
                          <table
                            width="100%"
                            border="0"
                            cellspacing="0"
                            cellpadding="0"
                            bgcolor="#ffffff"
                          >
                            <tr>
                              <td class="p30-15" style="padding: 50px 40px;">
                                <table
                                  width="100%"
                                  border="0"
                                  cellspacing="0"
                                  cellpadding="0"
                                >
                                  <tr>
                                    <td
                                      class="h3 pb10"
                                      style="
                                        color: #000000;
                                        font-family: 'Playfair Display', Georgia,
                                          serif;
                                        font-size: 22px;
                                        line-height: 32px;
                                        text-align: left;
                                        font-weight: bold;
                                        padding-bottom: 10px;
                                      "
                                    >
                                      <multiline>
                                        Confirmación de la reservación
                                      </multiline>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td
                                      class="text"
                                      style="
                                        color: #999999;
                                        font-family: Arial, sans-serif;
                                        font-size: 14px;
                                        line-height: 26px;
                                        text-align: left;
                                      "
                                    >
                                      <multiline>
										Actualmente usted tiene una reservación de hospedaje registrada con nosotros, 
										es necesario confirmar los terminos y condiciones para poder serguir con el proceso de reservación.
                                      </multiline>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </layout>
                        <!-- END Title + Copy -->


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
                                  TERMINOS Y CONDICIONES
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

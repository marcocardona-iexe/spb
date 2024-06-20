<?php

class materiasactivas
{

    function mapp()
    {
        $materias = array(
            "MAPP10" => "Primer_trimestre",
            "MAPP11" => "Primer_trimestre",
            "MAPP12" => "Primer_trimestre",

            "MAPP20" => "Segundo_trimestre",
            "MAPP21" => "Segundo_trimestre",
            "MAPP22" => "Segundo_trimestre",

            "MAPP30" => "Tercer_trimestre",
            "MAPP31" => "Tercer_trimestre",
            "MAPP32" => "Tercer_trimestre",

            "MAPP40" => "Cuarto_trimestre",
            "MAPP41" => "Cuarto_trimestre",
            "MAPP42" => "Cuarto_trimestre",

            "MAPP50" => "Quinto_trimestre",
            "MAPP51" => "Quinto_trimestre",
            "MAPP52" => "Quinto_trimestre"
        );
        return $materias;
    }


    function mepp()
    {
        $materias = array(
            "MEPP10" => "Primer_trimestre",
            "MEPP11" => "Primer_trimestre",
            "MEPP12" => "Primer_trimestre",

            "MEPP20" => "Segundo_trimestre",
            "MEPP21" => "Segundo_trimestre",
            "MEPP22" => "Segundo_trimestre",

            "MEPP30" => "Tercer_trimestre",
            "MEPP31" => "Tercer_trimestre",
            "MEPP32" => "Tercer_trimestre",

            "MEPP40" => "Cuarto_trimestre",
            "MEPP41" => "Cuarto_trimestre",
            "MEPP42" => "Cuarto_trimestre",

            "MEPP50" => "Quinto_trimestre",
            "MEPP51" => "Quinto_trimestre",
            "MEPP52" => "Quinto_trimestre"
        );
        return $materias;
    }


    function mspp()
    {
        $materias = array(
            "MSP11" => "Primer_trimestre",
            "MSP12" => "Primer_trimestre",
            "MSP13" => "Primer_trimestre",

            "MSP21" => "Segundo_trimestre",
            "MSP22" => "Segundo_trimestre",
            "MSP23" => "Segundo_trimestre",

            "MSP31" => "Tercer_trimestre",
            "MSP32" => "Tercer_trimestre",
            "MSP33" => "Tercer_trimestre",

            "MSP41" => "Cuarto_trimestre",
            "MSP42" => "Cuarto_trimestre",
            "MSP43" => "Cuarto_trimestre",

            "MSP51" => "Quinto_trimestre",
            "MSP52" => "Quinto_trimestre",
            "MSP53" => "Quinto_trimestre"
        );
        return $materias;
    }


    function maestrias($matricula)
    {
        #Man
        if (stristr($matricula, "man")) {
            $materias = array(
                "MBA11" => "Primer_trimestre",
                "MBA12" => "Primer_trimestre",
                "MBA13" => "Primer_trimestre",

                "MBA21" => "Segundo_trimestre",
                "MBA22" => "Segundo_trimestre",
                "MBA23" => "Segundo_trimestre",

                "MBA31" => "Tercer_trimestre",
                "MBA32" => "Tercer_trimestre",
                "MBA33" => "Tercer_trimestre",

                "MBA41" => "Cuarto_trimestre",
                "MBA42" => "Cuarto_trimestre",
                "MBA43" => "Cuarto_trimestre",

                "MBA51" => "Quinto_trimestre",
                "MBA52" => "Quinto_trimestre",
                "MBA53" => "Quinto_trimestre"
            );
        } elseif (stristr($matricula, "mfp")) {
            $materias = array(
                "MFP11" => "Primer_trimestre",
                "MFP12" => "Primer_trimestre",
                "MFP13" => "Primer_trimestre",

                "MFP21" => "Segundo_trimestre",
                "MFP22" => "Segundo_trimestre",
                "MFP23" => "Segundo_trimestre",

                "MFP31" => "Tercer_trimestre",
                "MFP32" => "Tercer_trimestre",
                "MFP33" => "Tercer_trimestre",

                "MFP41" => "Cuarto_trimestre",
                "MFP42" => "Cuarto_trimestre",
                "MFP43" => "Cuarto_trimestre",

                "MFP51" => "Quinto_trimestre",
                "MFP52" => "Quinto_trimestre",
                "MFP53" => "Quinto_trimestre",
            );
        } else {
            $materias = array();
        }
        return $materias;
    }


    function lic($matricula)
    {
        if (stristr($matricula, "lae")) {
            $materias = array(
                "LAE14" => "Primer_cuatrimestre",
                "LAE12" => "Primer_cuatrimestre",
                "LAE11" => "Primer_cuatrimestre",
                "LAE13" => "Primer_cuatrimestre",
                "LAE21" => "Segundo_cuatrimestre",
                "LAE22" => "Segundo_cuatrimestre",
                "LAE23" => "Segundo_cuatrimestre",
                "LAE24" => "Segundo_cuatrimestre",
                "LAE31" => "Tercer_cuatrimestre",
                "LAE32" => "Tercer_cuatrimestre",
                "LAE33" => "Tercer_cuatrimestre",
                "LAE34" => "Tercer_cuatrimestre",
                "LAE41" => "Cuarto_cuatrimestre",
                "LAE42" => "Cuarto_cuatrimestre",
                "LAE43" => "Cuarto_cuatrimestre",
                "LAE44" => "Cuarto_cuatrimestre",
                "LAE51" => "Quinto_cuatrimestre",
                "LAE52" => "Quinto_cuatrimestre",
                "LAE53" => "Quinto_cuatrimestre",
                "LAE54" => "Quinto_cuatrimestre",
                "LAE61" => "Sexto_cuatrimestre",
                "LAE62" => "Sexto_cuatrimestre",
                "LAE63" => "Sexto_cuatrimestre",
                "LAE64" => "Sexto_cuatrimestre",
                "LAE71" => "Séptimo_cuatrimestre",
                "LAE72" => "Séptimo_cuatrimestre",
                "LAE73" => "Séptimo_cuatrimestre",
                "LAE74" => "Séptimo_cuatrimestre",
                "LAE81" => "Octavo_cuatrimestre",
                "LAE82" => "Octavo_cuatrimestre",
                "LAE83" => "Octavo_cuatrimestre",
                "LAE84" => "Octavo_cuatrimestre",
                "LAE85" => "Octavo_cuatrimestre",
                "LAE86" => "Octavo_cuatrimestre",
                "LAE91" => "Noveno_cuatrimestre",
                "LAE92" => "Noveno_cuatrimestre",
                "LAE93" => "Noveno_cuatrimestre",
                "LAE94" => "Noveno_cuatrimestre",
                "LAE95" => "Noveno_cuatrimestre",
                "LAE96" => "Noveno_cuatrimestre"
            );
        } elseif (stristr($matricula, "ld")) {
            $materias = array(
                "LD11"  => "Primer_cuatrimestre",
                "LD12"  => "Primer_cuatrimestre",
                "LD13"  => "Primer_cuatrimestre",
                "LD14"  => "Primer_cuatrimestre",

                "LD21"  => "Segundo_cuatrimestre",
                "LD22"  => "Segundo_cuatrimestre",
                "LD23"  => "Segundo_cuatrimestre",
                "LD24"  => "Segundo_cuatrimestre",

                "LD31"  => "Tercer_cuatrimestre",
                "LD32"  => "Tercer_cuatrimestre",
                "LD33"  => "Tercer_cuatrimestre",
                "LD34"  => "Tercer_cuatrimestre",

                "LD41"  => "Cuarto_cuatrimestre",
                "LD42"  => "Cuarto_cuatrimestre",
                "LD43"  => "Cuarto_cuatrimestre",
                "LD44"  => "Cuarto_cuatrimestre",

                "LD51"  => "Quinto_cuatrimestre",
                "LD52"  => "Quinto_cuatrimestre",
                "LD53"  => "Quinto_cuatrimestre",
                "LD54"  => "Quinto_cuatrimestre",

                "LD61"  => "Sexto_cuatrimestre",
                "LD62"  => "Sexto_cuatrimestre",
                "LD63"  => "Sexto_cuatrimestre",
                "LD64"  => "Sexto_cuatrimestre",

                "LD71"  => "Séptimo_cuatrimestre",
                "LD72"  => "Séptimo_cuatrimestre",
                "LD73"  => "Séptimo_cuatrimestre",
                "LD74"  => "Séptimo_cuatrimestre",

                "LD81"  => "Octavo_cuatrimestre",
                "LD82"  => "Octavo_cuatrimestre",
                "LD83"  => "Octavo_cuatrimestre",
                "LD84"  => "Octavo_cuatrimestre",


                "LD91"  => "Noveno_cuatrimestre",
                "LD92"  => "Noveno_cuatrimestre",
                "LD93"  => "Noveno_cuatrimestre",
                "LD94"  => "Noveno_cuatrimestre",
                "LD95"  => "Noveno_cuatrimestre",
                "LD96"  => "Noveno_cuatrimestre",

                "LD101" => "Décimo_cuatrimestre",
                "LD102" => "Décimo_cuatrimestre",
                "LD103" => "Décimo_cuatrimestre",
                "LD104" => "Décimo_cuatrimestre",
                "LD105" => "Décimo_cuatrimestre",
                "LD106" => "Décimo_cuatrimestre"
            );
        } elseif (stristr($matricula, "lsp")) {
            $materias = array(
                "LSP11" => "Primer_cuatrimestre",
                "LSP12" => "Primer_cuatrimestre",
                "LSP13" => "Primer_cuatrimestre",
                "LSP14" => "Primer_cuatrimestre",

                "LSP21" => "Segundo_cuatrimestre",
                "LSP22" => "Segundo_cuatrimestre",
                "LSP23" => "Segundo_cuatrimestre",
                "LSP24" => "Segundo_cuatrimestre",

                "LSP31" => "Tercer_cuatrimestre",
                "LSP32" => "Tercer_cuatrimestre",
                "LSP33" => "Tercer_cuatrimestre",
                "LSP34" => "Tercer_cuatrimestre",

                "LSP41" => "Cuarto_cuatrimestre",
                "LSP42" => "Cuarto_cuatrimestre",
                "LSP43" => "Cuarto_cuatrimestre",
                "LSP44" => "Cuarto_cuatrimestre",

                "LSP51" => "Quinto_cuatrimestre",
                "LSP52" => "Quinto_cuatrimestre",
                "LSP53" => "Quinto_cuatrimestre",
                "LSP54" => "Quinto_cuatrimestre",

                "LSP61" => "Sexto_cuatrimestre",
                "LSP62" => "Sexto_cuatrimestre",
                "LSP63" => "Sexto_cuatrimestre",
                "LSP64" => "Sexto_cuatrimestre",

                "LSP71" => "Séptimo_cuatrimestre",
                "LSP72" => "Séptimo_cuatrimestre",
                "LSP73" => "Séptimo_cuatrimestre",
                "LSP74" => "Séptimo_cuatrimestre",

                "LSP81" => "Octavo_cuatrimestre",
                "LSP82" => "Octavo_cuatrimestre",
                "LSP83" => "Octavo_cuatrimestre",
                "LSP84" => "Octavo_cuatrimestre",
                "LSP85" => "Octavo_cuatrimestre",
                "LSP86" => "Octavo_cuatrimestre",

                "LSP91" => "Noveno_cuatrimestre",
                "LSP92" => "Noveno_cuatrimestre",
                "LSP93" => "Noveno_cuatrimestre",
                "LSP94" => "Noveno_cuatrimestre",
                "LSP95" => "Noveno_cuatrimestre",
                "LSP96" => "Noveno_cuatrimestre"
            );
        } elseif (stristr($matricula, "lcpap")) {
            $materias = array(
                "LCPAP11" => "Primer_cuatrimestre",
                "LCPAP12" => "Primer_cuatrimestre",
                "LCPAP13" => "Primer_cuatrimestre",
                "LCPAP14" => "Primer_cuatrimestre",

                "LCPAP21" => "Segundo_cuatrimestre",
                "LCPAP22" => "Segundo_cuatrimestre",
                "LCPAP23" => "Segundo_cuatrimestre",
                "LCPAP24" => "Segundo_cuatrimestre",

                "LCPAP31" => "Tercer_cuatrimestre",
                "LCPAP32" => "Tercer_cuatrimestre",
                "LCPAP33" => "Tercer_cuatrimestre",
                "LCPAP34" => "Tercer_cuatrimestre",

                "LCPAP41" => "Cuarto_cuatrimestre",
                "LCPAP42" => "Cuarto_cuatrimestre",
                "LCPAP43" => "Cuarto_cuatrimestre",
                "LCPAP44" => "Cuarto_cuatrimestre",

                "LCPAP51" => "Quinto_cuatrimestre",
                "LCPAP52" => "Quinto_cuatrimestre",
                "LCPAP53" => "Quinto_cuatrimestre",
                "LCPAP54" => "Quinto_cuatrimestre",

                "LCPAP61" => "Sexto_cuatrimestre",
                "LCPAP62" => "Sexto_cuatrimestre",
                "LCPAP63" => "Sexto_cuatrimestre",
                "LCPAP64" => "Sexto_cuatrimestre",

                "LCPAP71" => "Séptimo_cuatrimestre",
                "LCPAP72" => "Séptimo_cuatrimestre",
                "LCPAP73" => "Séptimo_cuatrimestre",
                "LCPAP74" => "Séptimo_cuatrimestre",

                "LCPAP81" => "Octavo_cuatrimestre",
                "LCPAP82" => "Octavo_cuatrimestre",
                "LCPAP83" => "Octavo_cuatrimestre",
                "LCPAP84" => "Octavo_cuatrimestre",
                "LCPAP85" => "Octavo_cuatrimestre",
                "LCPAP86" => "Octavo_cuatrimestre",

                "LCPAP91" => "Noveno_cuatrimestre",
                "LCPAP92" => "Noveno_cuatrimestre",
                "LCPAP93" => "Noveno_cuatrimestre",
                "LCPAP94" => "Noveno_cuatrimestre",
                "LCPAP95" => "Noveno_cuatrimestre",
                "LCPAP96" => "Noveno_cuatrimestre"
            );
        } else {
            $materias = array();
        }
        return $materias;
    }

    function doctorados($matricula)
    {
        if (stristr($matricula, "dpp")) {
            $materias = array(
                "DPP11" => "Primer_cuatrimestre",
                "DPP12" => "Primer_cuatrimestre",
                "DPP13" => "Primer_cuatrimestre",
                "DPP21" => "Segundo_cuatrimestre",
                "DPP22" => "Segundo_cuatrimestre",
                "DPP23" => "Segundo_cuatrimestre",
                "DPP31" => "Tercer_cuatrimestre",
                "DPP32" => "Tercer_cuatrimestre",
                "DPP33" => "Tercer_cuatrimestre",
                "DPP43" => "Cuarto_cuatrimestre",
                "DPP53" => "Quinto_cuatrimestre",
                "DPP63" => "Sexto_cuatrimestre",
                "DPP73" => "Séptimo_cuatrimestre",
                "DPP83" => "Octavo_cuatrimestre"
            );
        } elseif (stristr($matricula, "dsp")) {
            $materias = array(
                "DSP11" => "Primer_cuatrimestre",
                "DSP12" => "Primer_cuatrimestre",
                "DSP13" => "Primer_cuatrimestre",
                "DSP21" => "Segundo_cuatrimestre",
                "DSP22" => "Segundo_cuatrimestre",
                "DSP23" => "Segundo_cuatrimestre",
                "DSP31" => "Tercer_cuatrimestre",
                "DSP32" => "Tercer_cuatrimestre",
                "DSP33" => "Tercer_cuatrimestre",
                "DSP41" => "Cuarto_cuatrimestre",
                "DSP42" => "Cuarto_cuatrimestre",
                "DSP43" => "Cuarto_cuatrimestre",
                "DSP53" => "Quinto_cuatrimestre",
                "DSP63" => "Sexto_cuatrimestre",
                "DSP73" => "Séptimo_cuatrimestre",
                "DSP83" => "Octavo_cuatrimestre"
            );
        } else {
            $materias = array();
        }

        return $materias;
    }

    function mige()
    {

        $materias = array(
            "MIGE101"   => "Primer_trimestre",
            "MIGE102"   => "Primer_trimestre",
            "MIGE103"   => "Primer_trimestre",

            "MIGE201"   => "Segundo_trimestre",
            "MIGE202"   => "Segundo_trimestre",
            "MIGE203"   => "Segundo_trimestre",

            "MIGE301"   => "Tercer_trimestre",
            "MIGE302"   => "Tercer_trimestre",
            "MIGE303"   => "Tercer_trimestre",

            "MIGE401"   => "Cuarto_trimestre",
            "MIGE402"   => "Cuarto_trimestre",
            "MIGE403"   => "Cuarto_trimestre",
            /* SE QUITAN ESTAS CLAVES A PETICION DE TI
				"MIGE500"   => "Quinto_trimestre",
				"MIGE501"   => "Quinto_trimestre",
				"MIGE502"   => "Quinto_trimestre",
				*/
            //optativas 1
            "MIGEOPI1"   => "Quinto_trimestre",
            "MIGEOPI2"   => "Quinto_trimestre",
            "MIGEOPI3"   => "Quinto_trimestre",

            "MIGEOPI2"  => "Quinto_trimestre",
            "MIGEOPI3"  => "Quinto_trimestre",
            //optativa2
            "MIGEOPII1" => "Quinto_trimestre",
            "MIGEOPII2" => "Quinto_trimestre",
            "MIGEOPII3" => "Quinto_trimestre",
            "MIGE601"   => "Quinto_trimestre"
        );
        return $materias;
    }


    function tec($matricula, $banderamitic = 0)
    {

        if (stristr($matricula, "miti") && $banderamitic == 0) {
            $materias = array(
                "MITI11" => "Primer_trimestre",
                "MITI12" => "Primer_trimestre",
                "MITI13" => "Primer_trimestre",
                "MITI21" => "Segundo_trimestre",
                "MITI22" => "Segundo_trimestre",
                "MITI23" => "Segundo_trimestre",
                "MITI31" => "Tercer_trimestre",
                "MITI32" => "Tercer_trimestre",
                "MITI33" => "Tercer_trimestre",
                "MITI41" => "Cuarto_trimestre",
                "MITI42" => "Cuarto_trimestre",
                "MITI43" => "Cuarto_trimestre",
                "MITI51" => "Quinto_trimestre",
                "MITI52" => "Quinto_trimestre",
                "MITI53" => "Quinto_trimestre",
                "MITI54" => "Quinto_trimestre",

                "MITC10" => "Primer_trimestre",
                "MITC11" => "Primer_trimestre",
                "MITC12" => "Primer_trimestre",

                "MITC20" => "Segundo_trimestre",
                "MITC21" => "Segundo_trimestre",
                "MITC22" => "Segundo_trimestre",

                "MITC30" => "Tercer_trimestre",
                "MITC31" => "Tercer_trimestre",
                "MITC32" => "Tercer_trimestre",

                "MITC40" => "Cuarto_trimestre",
                "MITC41" => "Cuarto_trimestre",
                "MITC42" => "Cuarto_trimestre",

                "MITC50" => "Quinto_trimestre",
                "MITC51" => "Quinto_trimestre",
                "MITC52" => "Quinto_trimestre",
            );
        } elseif (stristr($matricula, "miti") && $banderamitic == 1) {
            $materias = array(
                "MITC10" => "Primer_trimestre",
                "MITC11" => "Primer_trimestre",
                "MITC12" => "Primer_trimestre",

                "MITC20" => "Segundo_trimestre",
                "MITC21" => "Segundo_trimestre",
                "MITC22" => "Segundo_trimestre",

                "MITC30" => "Tercer_trimestre",
                "MITC31" => "Tercer_trimestre",
                "MITC32" => "Tercer_trimestre",

                "MITC40" => "Cuarto_trimestre",
                "MITC41" => "Cuarto_trimestre",
                "MITC42" => "Cuarto_trimestre",

                "MITC50" => "Quinto_trimestre",
                "MITC51" => "Quinto_trimestre",
                "MITC52" => "Quinto_trimestre",
            );
        } elseif (stristr($matricula, "mcda")) {
            $materias = array(
                "MCDA101" => "Primer_trimestre",
                "MCDA102" => "Primer_trimestre",
                "MCDA103" => "Primer_trimestre",
                "MCDA201" => "Segundo_trimestre",
                "MCDA202" => "Segundo_trimestre",
                "MCDA203" => "Segundo_trimestre",
                "MCDA301" => "Tercer_trimestre",
                "MCDA302" => "Tercer_trimestre",
                "MCDA303" => "Tercer_trimestre",
                "MCDA401" => "Cuarto_trimestre",
                "MCDA402" => "Cuarto_trimestre",
                "MCDA403" => "Cuarto_trimestre",
                "MCDA501" => "Quinto_trimestre",
                "MCDA502" => "Quinto_trimestre",
                "MCDA503" => "Quinto_trimestre",
                "MCDA601" => "Sexto_trimestre"

            );
        } elseif (stristr($matricula, "mcdia")) {
            $materias = array(
                "MCDA101" => "Primer_trimestre",
                "MCDA102" => "Primer_trimestre",
                "MCDA103" => "Primer_trimestre",
                "MCDA201" => "Segundo_trimestre",
                "MCDA202" => "Segundo_trimestre",
                "MCDA203" => "Segundo_trimestre",
                "MCDA301" => "Tercer_trimestre",
                "MCDA302" => "Tercer_trimestre",
                "MCDA303" => "Tercer_trimestre",
                "MCDA401" => "Cuarto_trimestre",
                "MCDA402" => "Cuarto_trimestre",
                "MCDA403" => "Cuarto_trimestre",
                "MCDA501" => "Quinto_trimestre",
                "MCDA502" => "Quinto_trimestre",
                "MCDA503" => "Quinto_trimestre",
                "MCDA601" => "Sexto_trimestre"

            );
        } elseif (stristr($matricula, "mba")) {
            $materias = array(
                "MBA11" => "Primer_trimestre",
                "MBA12" => "Primer_trimestre",
                "MBA13" => "Primer_trimestre",
                "MBA21" => "Segundo_trimestre",
                "MBA22" => "Segundo_trimestre",
                "MBA23" => "Segundo_trimestre",
                "MBA31" => "Tercer_trimestre",
                "MBA32" => "Tercer_trimestre",
                "MBA33" => "Tercer_trimestre",
                "MBA41" => "Cuarto_trimestre",
                "MBA42" => "Cuarto_trimestre",
                "MBA43" => "Cuarto_trimestre",
                "MBA51" => "Quinto_trimestre",
                "MBA52" => "Quinto_trimestre",
                "MBA53" => "Quinto_trimestre",
            );
        } else {
            $materias = array();
        }
        return $materias;
    }



    function master($matricula)
    {
        if (stristr($matricula, "mais")) {
            $materias = array(
                "MADS101" => "Primer_trimestre",
                "MADS102" => "Primer_trimestre",
                "MADS103" => "Primer_trimestre",

                "MADS201" => "Segundo_trimestre",
                "MADS202" => "Segundo_trimestre",
                "MADS203" => "Segundo_trimestre",

                "MADS301" => "Tercer_trimestre",
                "MADS302" => "Tercer_trimestre",
                "MADS303" => "Tercer_trimestre",

                "MADS401" => "Cuarto_trimestre",
                "MADS402" => "Cuarto_trimestre",
                "MADS403" => "Cuarto_trimestre",

                "MADS501" => "Quinto_trimestre",
                "MADS502" => "Quinto_trimestre",
                "MADS503" => "Quinto_trimestre",

                "MADS601" => "Sexto_trimestre"
            );
        } elseif (stristr($matricula, "mag")) {
            $materias = array(
                "MAG100" => "Primer_trimestre",
                "MAG101" => "Primer_trimestre",
                "MAG102" => "Primer_trimestre",
                "MAG200" => "Segundo_trimestre",
                "MAG201" => "Segundo_trimestre",
                "MAG202" => "Segundo_trimestre",
                "MAG300" => "Tercer_trimestre",
                "MAG301" => "Tercer_trimestre",
                "MAG302" => "Tercer_trimestre",
                "MAG400" => "Cuarto_trimestre",
                "MAG401" => "Cuarto_trimestre",
                "MAG402" => "Cuarto_trimestre",
                "MAG500" => "Quinto_trimestre",
                "MAG501" => "Quinto_trimestre",
                "MAG502" => "Quinto_trimestre"
            );
        } elseif (stristr($matricula, "mgpm")) {
            $materias = array(
                "MGPM100" => "Primer_trimestre",
                "MGPM101" => "Primer_trimestre",
                "MGPM102" => "Primer_trimestre",

                "MGPM200" => "Segundo_trimestre",
                "MGPM201" => "Segundo_trimestre",
                "MGPM202" => "Segundo_trimestre",

                "MGPM300" => "Tercer_trimestre",
                "MGPM301" => "Tercer_trimestre",
                "MGPM302" => "Tercer_trimestre",

                "MGPM400" => "Cuarto_trimestre",
                "MGPM401" => "Cuarto_trimestre",
                "MGPM402" => "Cuarto_trimestre",

                "MGPM500" => "Quinto_trimestre",
                "MGPM501" => "Quinto_trimestre",
                "MGPM502" => "Quinto_trimestre"
            );
        } elseif (stristr($matricula, "mspajo")) {
            $materias = array(
                "MSPAJO100" => "Primer_trimestre",
                "MSPAJO101" => "Primer_trimestre",
                "MSPAJO102" => "Primer_trimestre",

                "MSPAJO200" => "Segundo_trimestre",
                "MSPAJO201" => "Segundo_trimestre",
                "MSPAJO202" => "Segundo_trimestre",

                "MSPAJO300" => "Tercer_trimestre",
                "MSPAJO301" => "Tercer_trimestre",
                "MSPAJO302" => "Tercer_trimestre",

                "MSPAJO400" => "Cuarto_trimestre",
                "MSPAJO401" => "Cuarto_trimestre",
                "MSPAJO402" => "Cuarto_trimestre",

                "MSPAJO500" => "Quinto_trimestre",
                "MSPAJO501" => "Quinto_trimestre",
                "MSPAJO502" => "Quinto_trimestre",

                "MSPAJO600" => "Sexto_trimestre"
            );
        } elseif (stristr($matricula, "mmpop")) {
            $materias = array(
                "MMPOP100" => "Primer_trimestre",
                "MMPOP101" => "Primer_trimestre",
                "MMPOP102" => "Primer_trimestre",

                "MMPOP200" => "Segundo_trimestre",
                "MMPOP201" => "Segundo_trimestre",
                "MMPOP202" => "Segundo_trimestre",

                "MMPOP300" => "Tercer_trimestre",
                "MMPOP301" => "Tercer_trimestre",
                "MMPOP302" => "Tercer_trimestre",

                "MMPOP400" => "Cuarto_trimestre",
                "MMPOP401" => "Cuarto_trimestre",
                "MMPOP402" => "Cuarto_trimestre",

                "MMPOP500" => "Quinto_trimestre",
                "MMPOP501" => "Quinto_trimestre",
                "MMPOP502" => "Quinto_trimestre"
            );
        }

        return $materias;
    }
}

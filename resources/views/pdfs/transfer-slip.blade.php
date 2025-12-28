<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bordereau De Remise De Virements</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 10px;
        }

        th, td {
            padding: 6px;
            vertical-align: middle;
            word-wrap: break-word;
             border: none
        }

        .border-black {

             border: 1px solid #000 !important;
        }

        .bold {
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }



        .title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0 30px 0;
        }

        /* Remove top/bottom borders for some cells */

    </style>
</head>
<body>

<h2 class="title">Bordereau De Remise De Virements de Salaires Sous Support CD ou Clé USB</h2>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<table>

    <tr>
        <td class="bold " width="25%" >Nom / Raison Sociale du client</td>
        <td class="border-black text-center" colspan="4" >Structure de gestion des oeuvres sociales Ehs Mère et Enfant Tlemcen</td>
    </tr>
 <tr><td></td></tr> <tr><td></td></tr>
    <tr>
        <td class="bold">Agence de Domiciliation</td>
        <td  class="border-black text-center">{{ $agencyName }}</td>
        <td colspan="2"></td>
        <td class="bold">Code Agence de Domiciliation</td>
        <td class="border-black text-center">{{ $agencyCode }}</td>
    </tr>
   <tr><td></td></tr>
    <tr>
        <td class="bold ">Compte à Débiter</td>
        <td  class="border-black text-center">{{ $account }}</td>
         <td colspan="2"></td>
        <td class="bold">Clé</td>
        <td class="border-black text-center">{{ $accountKey }}</td>
    </tr>
 <tr><td></td></tr> <tr><td></td></tr>
    <tr>
        <td class="bold">Référence de la Remise</td>
        <td class="border-black text-center">{{ $reference }}</td>
        <td colspan="3" ></td>
    </tr>
 <tr><td></td></tr> <tr><td></td></tr>
    <tr>
        <td class="bold">Date d'execution</td>
        <td class="border-black   text-center">{{ $date }}</td>
        <td colspan="3" ></td>
    </tr>
 <tr><td></td></tr> <tr><td></td></tr>
    <tr>
        <td class="bold">Montant Total de la Remise</td>
        <td  class="border-black   text-center">{{ $totalAmount }} DA</td>
       <td colspan="2"></td>
    </tr>
 <tr><td></td></tr> <tr><td></td></tr>
    <tr>
        <td class="bold">Nombre d'Opérations</td>
        <td class="border-black text-center" >{{ $numberOperations }}</td>
        <td colspan="3" ></td>
    </tr>
</table>


<br>
<br>
<br>
<br>
<br>
<br>
<table>
    <tr>

        <td class="bold text-right" colspan="4">Fait à TLEMCEN</td>
    </tr>
    <tr>
        <td colspan="4" class="text-right"> le 09/03/2025</td>
    </tr>
</table>

<br><br>

<table>
    <tr>
        <td class="text-center bold ">Signature (s) Autorisée (s) et Cachet</td>
    </tr>
</table>

</body>
</html>

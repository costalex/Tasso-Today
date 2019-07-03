<template>
    <LegalDocument>
        <h1>Mentions Légales</h1>

        <p>
            <strong>TASSO</strong>
        </p>

        <p>
            SAS au capital de <strong>1000 euros</strong>
        </p>

        <p>
            <strong>Siège social</strong> : 9 rue André Darbon, 33300, Bordeaux, France
        </p>

        <p>
            <strong>RCS</strong> : 827 480 138 BORDEAUX
        </p>

        <p>
            <strong>TVA</strong> : FR33827480138
        </p>

        <p>
            <strong>Adresse électronique</strong> : contact@tasso.today
        </p>

        <p>
            <strong>Numéro de téléphone</strong> : +33 5 24 07 80 40
        </p>

        <p>
            <strong>Directeur de la publication le Président de la SAS</strong> : Alexandre Costa
        </p>

        <p>
            <strong>Hébergeur</strong> : OVH, 2 rue Kellermann, BP 80157, 59100 Roubaix
        </p>
    </LegalDocument>
</template>

<script>
import LegalDocument from '@/components/LegalDocument';

export default {
    name: 'LegalNotice',
    components: { LegalDocument }
};
</script>

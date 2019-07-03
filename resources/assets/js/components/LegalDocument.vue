<template>
    <div class="legal-document">
        <slot/>
    </div>
</template>

<style lang="scss" scoped>
.legal-document {
    max-width: 1200px;
    width: 90%;
    margin: 0 auto;
    text-align: justify;

    h1 {
        font-family: "Rubik", Verdana, Sans-serif;
        font-size: 42px;
        font-weight: 300;
        text-align: center;
        padding: 2rem 0;
        margin-bottom: 40px;
        color: #2D3138;
    }

    h2 {
        font-family: "Rubik", Verdana, Sans-serif;
        color: #2D3138;
        margin-top: 40px;
    }

    ul {
        font-family: "Rubik", Verdana, Sans-serif;
        font-weight: 400;
    }

    p {
        font-family: "Rubik", Verdana, Sans-serif;
        font-weight: 300;
        font-size: 16px;
        color: #606062;

        strong {
            font-weight: 400;
            color: #2D3138;
        }
    }
}

@media only screen and (min-width: 1024px) {
    .legal-document {
        width: 65%;
    }
}
</style>

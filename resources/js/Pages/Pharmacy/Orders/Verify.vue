<template>
      <h1>Welcome from Our QR Code Scanner</h1>
    <p>We can verify that this prescription exists</p>
    <p>Prescription Code: {{ code }}</p>

    <pre>{{ prescription }}</pre>

</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: ['prescription'],
})
</script>

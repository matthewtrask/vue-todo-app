<style lang='scss' scoped>

</style>
<template>
    <div>
        <div class="alert alert-danger" role="alert">
            {{ msg }}
        </div>
    </div>
</template>
<script>
  import axios from 'axios';

  export default {
    props: ['msg'],
  };
</script>
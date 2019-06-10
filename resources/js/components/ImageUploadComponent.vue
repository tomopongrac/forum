<template>
    <div>
        <div class="custom-file mb-2">
            <input type="file" class="custom-file-input" id="customFile" name="avatar" accept="image/*"
                   @change="onChange">
            <label class="custom-file-label" for="customFile">Choose file</label>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            onChange(e) {
                if (!e.target.files.length) return;

                let file = e.target.files[0];

                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = e => {
                    this.$emit('loaded', {
                        src: e.target.result,
                        file: file
                    });
                };
            }
        }
    };
</script>

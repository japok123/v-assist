<script>
    export default {
        methods:{
            verifyEmail(email){
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailPattern.test(email);
            },
            formatDate(param) {

                const date = new Date(param);
                if (isNaN(date.getTime())) {
                    return 'Invalid Date';
                }
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0'); 
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
        }
    }

</script>
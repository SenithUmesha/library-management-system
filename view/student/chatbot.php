<style>
    .chatbox-popup {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        border: 1px solid #ccc;
        border-radius: 50%;
        overflow: hidden;
        cursor: pointer;
        background-color: #fff;
        color: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 18px;
    }

    .chatbox {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 300px;
        border: 1px solid #ccc;
        border-radius: 5px;
        overflow: hidden;
        display: none;
    }

    .chatbox-header {
        background-color: #007bff;
        color: #fff;
        padding: 10px;
        text-align: center;
        cursor: pointer;
    }

    .chatbox-body {
        height: 200px;
        overflow-y: auto;
        padding: 10px;
    }

    .message {
        margin-bottom: 10px;
    }

    .input-group {
        padding: 10px;
        border-top: 1px solid #ccc;
    }

    .input-group input {
        width: 70%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-right: 5px;
    }

    .input-group button {
        padding: 8px 15px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .chatbox-header::before {
        content: '\2190';
        position: absolute;
        left: 10px;
        font-size: 20px;
    }
</style>

<body>
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger intent="Welcome" chat-title="Library Assistant" agent-id="48015862-84d3-4935-a93c-c28f5b3137be" language-code="en"></df-messenger>
</body>


</script>
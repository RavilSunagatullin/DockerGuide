const express = require('express');
const app = express();
const port = process.env.PORT || 3000;

app.get('/', (_req, res) => {
  res.send('Hello from Node.js running in Docker!');
});

app.listen(port, () => {
  console.log(`Server is listening on port ${port}`);
});

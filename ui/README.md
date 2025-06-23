# Accounting UI

This is the React frontend for the Accounting API.

## Local development

```bash
cd ui
npm install
npm start
```

## Dockerized development

To build and run the React dev server in a container:

```bash
docker build -t accounting-ui .
docker run --rm -p 5173:5173 accounting-ui
```

Then open [http://localhost:5173](http://localhost:5173) in your browser.

## Environment variables

- `VITE_API_URL`: The base URL for the backend API.
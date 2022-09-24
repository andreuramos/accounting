FROM node:latest

WORKDIR /web

COPY package.json ./
COPY package-lock.json ./
COPY . ./

RUN npm install react-scripts@latest \
    && npm run build


CMD ["npm", "start"]

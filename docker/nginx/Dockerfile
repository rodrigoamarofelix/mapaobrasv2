FROM nginx:alpine

# Copiar configuração personalizada do Nginx
COPY docker/nginx/nginx.conf /etc/nginx/conf.d/default.conf

# Expor porta
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]

COMPOSE = docker compose
UPLOADS = /var/www/html/public/uploads

up:
	$(COMPOSE) up -d --build

down:
	$(COMPOSE) down

clean:
	-$(COMPOSE) run --rm --no-deps --entrypoint sh app -c \
		"find $(UPLOADS) -type f ! -name .gitkeep -delete"

fclean: clean
	$(COMPOSE) down -v

re: fclean up

.PHONY: up down clean fclean re
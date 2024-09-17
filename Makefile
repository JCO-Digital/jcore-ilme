all: install build

ci: install build

install:
	pnpm i

build:
	pnpm run build

watch:
	pnpm run watch

clean:
	rm -rf node_modules

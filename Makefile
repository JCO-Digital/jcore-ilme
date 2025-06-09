.PHONY: all dev ci ci-install install build watch clean

all: install build

dev: install watch

ci: ci-install build

ci-install: install

install:
	pnpm i

build:
	pnpm run build

watch:
	pnpm run watch

clean:
	rm -rf dist
	rm -rf node_modules

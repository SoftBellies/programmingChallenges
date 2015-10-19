void pcs(int intro, int width, char c) {
    int j;

    for(j = 0; j < intro; j++) putchar(' ');
    for(j = 0; j < width; j++) putchar(c);
    putchar('\n');
}

void draw(int stair, int width, int maxwidth) {
    int i;
    for(i = 0; i < stair + 3; i++, width += 2) {
        pcs((maxwidth - width)/2, width, '*');
    }
}

void draw_pied(int height, int maxwidth) {
    int i;
    for(i = 0; i < height; i++) {
        pcs((maxwidth - height) / 2, height + (height+1)%2, '|');
    }
}

void draw_sapin(int height) {
    int stair, incr, width, maxwidth;

    for(width = 1, incr = 2, stair = 1; stair <= height; stair++) {
        if((stair+1)%2 == 0) incr+=2;
        width += incr;
    }
    maxwidth = width + height + 2;

    for(width = 1, incr = 2, stair = 1; stair <= height; stair++) {
        draw(stair, width, maxwidth);
        if((stair+1)%2 == 0) incr+=2;
        width += incr;
    }

    draw_pied(height, maxwidth);
}

int getint(char *s) {
    int value = 0;
    char c;

    while(*s != '\0') {
        c = *s;
        if(c < '0' || c > '9') return -1;
        value = value * 10 + c - '0';
        s++;
    }
    return value;
}

int main(int argc, char **argv) {
    int height;
    if(argc != 2) return 1;
    height = getint(argv[1]);
    if (height < 1) return 2;
    draw_sapin(height);
    return 0;
}


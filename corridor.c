#include <stdio.h>
#include <stdlib.h>
#define BOARDPERPLAYER 10

int numOfPlayer=0;
int turn=0;
struct Player{
    int x;
    int y;
    int board;
};

struct Player player[4];

int board[64];

int boardh[8][9]={0};//[y][x]
int boardv[9][8]={0};

void startGame();
void endGame();
void genBoard();
void getPlayerNum();
void initPosition();
int getTurn();
char turn2player(int i);
int movej(int d);
int moveUpward(struct Player *p,int dummy);
int moveDownward(struct Player *p,int dummy);
int moveLeft(struct Player *p,int dummy);
int moveRight(struct Player *p,int dummy);
int placeBoard(char x,char y,int direction);
int checkboard[81]={0};
int checkAcs(int b);
int checkWin();
void clear();

int main(){
    int option;
    do{
        //retrieve menu from txt
        printf("1. start game\n2. quit\n");
        printf("Input your option: ");
        scanf("%d",&option);
        switch(option){
            case 1:
                //start pvp game
                clear();
                startGame();
                system("PAUSE");
            break;
            case 2:
                //end game
                endGame();
            break;
        }
    }while(option!=2);
    return 0;
}

void startGame(){
    int option,c;
    char optionxy[3];
    getPlayerNum();
    initPosition();
    system("mode 100,40");
    do{
        clear();
        genBoard();
        printf("\n");
        if(player[getTurn()].board<=0){
            printf("%c's turn:\n  1.move\n  Input your option: ",turn2player(getTurn()));
        }else{
            printf("%c's turn:\n  1.move\n  2.place board (%d)\n  Input your option: ",turn2player(getTurn()),player[getTurn()].board);
        }
        scanf("%d",&option);
        if(option<1 || option>2) continue;
        if(option==2 && player[getTurn()].board<=0) continue;
        switch(option){
            case 1:
                c=0;
                do{
                    if(c>0){
                        printf("    Illegal move!\n    Input your option: ");
                    }else{
                        c++;
                        printf("\n    Move..\n    1.upward\n    2.downward\n    3.left\n    4.right\n    5.BACK MENU\n    Input your option: ");
                    }
                    scanf("%d",&option);
                }while(movej(option)==0);
                break;
            case 2:
                c=0;
                do{
                    if(c>0){
                        printf("    Illegal placement!\n");
                    }
                    c++;
                    printf("\n    Place board\n    1.horizontally\n    2.vertically\n    3.BACK MENU\n    Input your option: ");
                    scanf("%d",&option);
                    if(option==3) break;
                    printf("      at(eg. 'Ab', 'Bc' etc.) or type 'r' to return back to main menu: ");
                    fflush(stdin);
                    gets(optionxy);
                    if(optionxy[0]=='r'){
                        break;
                    }
                }while(!placeBoard(optionxy[1],optionxy[0],option));
                break;
        }
    }while(checkWin()==-1);
    clear();
    genBoard();
    printf("%c wins!",turn2player(checkWin()));
    return;
}
void endGame(){
    return;
}
void genBoard(){
    int i=0;
    int x,y;
    int flag,dec;
    printf("   ");
    for(x=0;x<37;++x){
        if(x%4==0&&x>1&&x<35){
            printf("%c",(x/4)+96);
        }else{
            printf(" ");
        }
    }
    printf("\n");
    for(y=0;y<19;++y){
        if(y>1&&y<17&&y%2==0)
            printf(" %c ",(y/2)+64);
        else
            printf("   ");
        for(x=0;x<37;++x){
//            printf("%d, %d, %d",x,y,dec);
//          system("pause"); 
            switch(y%2){
                case 0:
                    dec=(y>0&&y<18&&x<36)?(8*y/2+x/4-9):-2;
                    if(x%4==0){
                        printf("+");
                        //printf("%d",dec);
                        break;
                    }
                    if(x%4==1 || x%4==2 || x%4==3){
                        if(x<36&&x>3&&board[dec]==1){
                            printf("-");
                            break;
                        }else if(x<33&&board[dec+1]==1){
                            printf("-");
                            break;
                            
                        }else{
                            printf(" ");
                            break;
                        }
                    }
                    break;
                case 1:
                    dec=(y>0&&y<18&&x>3&&x<36)?(8*(y-1)/2+x/4-9):-2;
                    if(x%4==1 || x%4==3){
                        printf(" ");
                        break;
                    }
                    if(x%4==0){
                        if(y<18&&(board[dec+8]==2||board[dec]==2)){
                            printf("|");
                            //printf("%d",dec);
                            break;
                        }else{
                            if(y==17&&board[dec]==2){
                                printf("|");
                                break;
                            }else{
                                printf(" ");
                                break;
                            }
                        }
                    }
                    if(x%4==2){
                        flag=0;
                        for(i=0;i<numOfPlayer;++i){
                            if(player[i].x==(x-2)/4 && player[i].y==(y-1)/2){
                                printf("%c",turn2player(i));
                                flag=1;
                            }
                        }
                        if(flag==0)
                            printf(" ");
                        break;
                    }
                    break;
            }
                 
        }
        printf("\n");
    }
    return;
}
void getPlayerNum(){
    int i;
    int n=0;
    while(n<2 || n>4){
        printf("Input the number of player: ");
        scanf("%d",&n);
    }
    numOfPlayer=n;
    for(i=0;i<4;++i){
        player[i].x=-1;
        player[i].y=-1;
        player[i].board=BOARDPERPLAYER;
    }
    return;
}
void initPosition(){
    int i;
    for(i=0;i<numOfPlayer;++i){
        if(i==0 || i==1){
            player[i].x=4;
        }else if(i==2){
            player[i].x=0;
        }else if(i==3){
            player[i].x=8;
        }
        if(i==2 || i==3){
            player[i].y=4;
        }else if(i==0){
            player[i].y=0;
        }else if(i==1){
            player[i].y=8;
        }
    }
}
int getTurn(){
    return turn%numOfPlayer;
}
char turn2player(int i){
    return 65+i;
}
int movej(int d){
    switch(d){
        case 1:
            if(moveUpward(&player[getTurn()],0)){
                turn++;
            }else{
                return 0;   
            }
            break;
        case 2:
            if(moveDownward(&player[getTurn()],0)){
                turn++;
            }else{
                return 0;   
            }
            break;
        case 3:
            if(moveLeft(&player[getTurn()],0)){
                turn++;
            }else{
                return 0;   
            }
            break;
        case 4:
            if(moveRight(&player[getTurn()],0)){
                turn++;
            }else{
                return 0;   
            }
            break;
        case 5:
            break;
        default:
            return 0;
            break;
    }
    return 1;
}
int moveUpward(struct Player *p,int dummy){
    int i;
    struct Player copy;
    copy=*p;
    if(dummy==0){
        for(i=0;i<numOfPlayer;++i){
            if(player[i].y==copy.y-1&&player[i].x==copy.x){
                return 0;
            }
        }
    }
    if(copy.y<1) return 0;
    if(copy.x<9){
        if(board[8*copy.y+copy.x-8]==1) return 0;
    }
    if(copy.x>0){
        if(board[8*copy.y+copy.x-9]==1) return 0;
    }
    copy.y--;
    *p=copy;
    return 1;
}
int moveDownward(struct Player *p,int dummy){
    int i;
    struct Player copy;
    copy=*p;
    if(dummy==0){
        for(i=0;i<numOfPlayer;++i){
            if(player[i].y==copy.y+1&&player[i].x==copy.x){
                return 0;
            }
        }
    }
    if(copy.y>7) return 0;
    if(copy.x<9){
        if(board[8*copy.y+copy.x]==1) return 0;
    }
    if(copy.x>0){
        if(board[8*copy.y+copy.x-1]==1) return 0;
    }
    copy.y++;
    *p=copy;
    return 1;
}
int moveLeft(struct Player *p,int dummy){
    int i;
    struct Player copy;
    copy=*p;
    if(dummy==0){
        for(i=0;i<numOfPlayer;++i){
            if(player[i].x==copy.x-1&&player[i].y==copy.y){
                return 0;
            }
        }
    }
    if(copy.x<1) return 0;
    if(board[8*copy.y+copy.x-1]==2) return 0;
    if(copy.y>0)
        if(board[8*copy.y+copy.x-9]==2) return 0;
    copy.x--;
    *p=copy;
    return 1;   
}
int moveRight(struct Player *p,int dummy){
    int i;
    struct Player copy;
    copy=*p;
    if(dummy==0){
        for(i=0;i<numOfPlayer;++i){
            if(player[i].x==copy.x+1&&player[i].y==copy.y){
                return 0;
            }
        }
    }
    if(copy.x>7) return 0;
    if(board[8*copy.y+copy.x]==2) return 0;
    if(copy.y>0)
        if(board[8*copy.y+copy.x-8]==2) return 0;
    copy.x++;
    *p=copy;
    return 1;   
}
int placeBoard(char x,char y,int direction){
    int a,b,i,dec;
    a=y-65;
    b=x-97;
    dec=8*a+b;
    /*printf("%d",dec);
    system("PAUSE");*/
    switch(direction){
        case 1:
            if(board[dec]!=0){
                return 0;
                break;
            }else if(board[dec-1]==1 || board[dec+1]==1){
                return 0;
                break;
            }else{
                board[dec]=1;
                for(i=0;i<81;++i){
                    checkboard[i]=0;
                }
                if(checkAcs(0)){
                    player[getTurn()].board--;
                    turn++;
                    return 1;
                }else{
                    board[dec]=0;
                    return 0;
                }
                break;
            }
        case 2:
            if(board[dec]!=0){
                return 0;
                break;
            }else if(board[dec-8]==2 || board[dec+8]==2){
                return 0;
                break;
            }else{
                board[dec]=2;
                for(i=0;i<81;++i){
                    checkboard[i]=0;
                }
                if(checkAcs(0)){
                    player[getTurn()].board--;
                    turn++;
                    return 1;
                }else{
                    board[dec]=0;
                    return 0;
                }
                break;
            }
        default:
            return 0;
            break;
    }
}
int checkAcs(int b){
    int i;
    int x=b%9,y=b/9;
    int flag,s=0;
    checkboard[b]=1;
    //move right
    flag=0;
    if(x>7) flag=1;
    if(board[8*y+x]==2) flag=1;
    if(y>0)
        if(board[8*y+x-8]==2) flag=1;
    if(checkboard[b+1]==1) flag=1;
    if(flag==0){
        checkAcs(b+1);
        s++;
    }
    //move left
    flag=0;
    if(x<1) flag=1;
    if(board[8*y+x-1]==2) flag=1;
    if(y>0)
        if(board[8*y+x-9]==2) flag=1;
    if(checkboard[b-1]==1) flag=1;
    if(flag==0){
        checkAcs(b-1);
        s++;
    }
    //move up
    flag=0; 
    if(y<1) flag=1;
    if(x<9){
        if(board[8*y+x-8]==1) flag=1;
    }
    if(x>0){
        if(board[8*y+x-9]==1) flag=1;
    }
    if(checkboard[b-9]==1) flag=1;
    if(flag==0){
        checkAcs(b-9);
        s++;
    }
    //move down
    flag=0;
    if(y>7) flag=1;
    if(x<9){
        if(board[8*y+x]==1) flag=1;
    }
    if(x>0){
        if(board[8*y+x-1]==1) flag=1;
    }
    if(checkboard[b+9]==1) flag=1;
    if(flag==0){
        checkAcs(b+9);
        s++;
    }
    
    
    if(b==0){
        for(i=0;i<81;++i){
            if(checkboard[i]==0){
                return 0;
            }
        }
        return 1;
    }else{
        return 0;
    }
}
int checkWin(){
    int i;
    for(i=0;i<numOfPlayer;++i){
        switch(i){
            case 0:
                if(player[i].y==8)
                    return i;
                break;
            case 1:
                if(player[i].y==0)
                    return i;
                break;
            case 2:
                if(player[i].x==8)
                    return i;
                break;
            case 3:
                if(player[i].x==0)
                    return i;
                break;
        }
    }
    return -1;
}
void clear(){
    system("cls");
    return;
}

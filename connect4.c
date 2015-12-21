#include <stdio.h>
#include <stdlib.h>
#include <time.h>

int board[6][7],r=0,sparkling[4][2];
char player[2][100];

void pause(int sec){
    int s,e;
    s=time(NULL);
    e=s;
    while(sec>e-s){
        e=time(NULL);
    }
}
void clear(){
    system("cls");
}
void line(){
    printf("|---||---||---||---||---||---||---|\n");
}
void gnrt(){
    int i,j;
    for(i=1;i<=7;i++){
        printf("  %d  ",i);
    }
    printf("\n");
    for(i=0;i<6;i++){
        line();
        for(j=0;j<7;j++){
            printf("|   |");
        }
        printf("\n");
        for(j=0;j<7;j++){
            printf("| %c |",board[i][j]);
        }
        printf("\n");
    }
    line();
}
void gnrt_s(){
    int i,j;
    while(1){
        clear();
        for(i=1;i<=7;i++){
            printf("  %d  ",i);
        }
        printf("\n");
        for(i=0;i<6;i++){
            line();
            for(j=0;j<7;j++){
                printf("|   |");
            }
            printf("\n");
            for(j=0;j<7;j++){
                printf("| %c |",board[i][j]);
            }
            printf("\n");
        }
        line();
        for(i=0;i<4;i++){
            board[sparkling[i][0]][sparkling[i][1]]=(board[sparkling[i][0]][sparkling[i][1]]==' ')?(r%2==0)?'@':'O':' ';
        }
        printf("%s wins\n",player[r%2]);
        pause(1);
    }
}
int input_f(int x){
    int i;
    x=x-1;
    if(x>=0&&x<=6){
        for(i=0;i<6;i++){
            if(board[i][x]!=' ' && i==0){
                return 0;
            }
            if(board[i][x]!=' '){
                board[i-1][x]=(r%2==0)?'@':'O';
                return 1;
            }else if(i==5 && board[i][x]==' '){
                board[i][x]=(r%2==0)?'@':'O';
                return 1;
            }
        }
    }
    return 0;
}

int checkwin(){
    int i,j,k;
    //horizontal
    for(i=0;i<6;i++){
        for(j=0;j<4;j++){
            if(board[i][j]==board[i][j+1]&&board[i][j+1]==board[i][j+2]&&board[i][j+2]==board[i][j+3]&&board[i][j]!=' '){
                for(k=0;k<4;k++){
                    sparkling[k][0]=i;
                    sparkling[k][1]=j+k;
                }
                return 1;
            }
        }
    }
    //vertical
    for(i=0;i<7;i++){
        for(j=0;j<3;j++){
            if(board[j][i]==board[j+1][i]&&board[j+1][i]==board[j+2][i]&&board[j+2][i]==board[j+3][i]&&board[j][i]!=' '){
                for(k=0;k<4;k++){
                    sparkling[k][0]=j+k;
                    sparkling[k][1]=i;
                }
                return 1;
            }
        }
    }
    //diagonal slope=(+)//
    for(i=0;i<3;i++){
        for(j=0;j<4;j++){
            if(board[i][j]==board[i+1][j+1]&&board[i+1][j+1]==board[i+2][j+2]&&board[i+2][j+2]==board[i+3][j+3]&&board[i][j]!=' '){
                for(k=0;k<4;k++){
                    sparkling[k][0]=i+k;
                    sparkling[k][1]=j+k;
                }
                return 1;
            }
        }
    }
    //diagonal slope=(-)//
    for(i=3;i<6;i++){
        for(j=0;j<4;j++){
            if(board[i][j]==board[i-1][j+1]&&board[i-1][j+1]==board[i-2][j+2]&&board[i-2][j+2]==board[i-3][j+3]&&board[i][j]!=' '){
                for(k=0;k<4;k++){
                    sparkling[k][0]=i-k;
                    sparkling[k][1]=j+k;
                }
                return 1;
            }
        }
    }
    return 0;
}
int ai(){
    int i,j,k,t=0;
    
    srand(time(NULL));
    //horizontal
    for(i=0;i<6;i++){
        for(j=0;j<4;j++){
            t=0;
            for(k=0;k<4;k++){
                if(board[i][j+k]=='@'){
                    t++;
                }
                if(board[i][j+k]=='O'){
                    t--;
                }
            }
            if(t>=3 || t<=-3){
                if(board[i][j]==' ' && board[i+1][j]!=' ' && j!=0){
                    return (rand()%1000<=499)?j+1:j+5;
                }
                if(board[i][j+3]==' ' && board[i+1][j+3]!=' ' && j!=3 && j!=0){
                    return (rand()%1000<=499)?j:j+4;
                }
                for(k=0;k<4;k++){
                    if(board[i][j+k]==' '&&board[i+1][j+k]!=' '){
                        return j+k+1;
                    }
                }
            }
        }
    }
    //vertical
    for(i=0;i<7;i++){
        for(j=3;j<6;j++){
            if(board[j][i]==board[j+1][i]&&board[j+1][i]==board[j+2][i]&&board[j-1][i]==' '&&board[j][i]!=' '){
                return i+1;
            }
        }
    }
    //diagonal slope=(-)//
    for(i=0;i<4;i++){
        for(j=0;j<5;j++){
            if(board[i][j]==board[i+1][j+1]&&board[i+1][j+1]==board[i+2][j+2]&&board[i][j]!=' '){
                if((i==3&&j==0) || (i==0&&j==4)){
                    continue;
                }else if(i==0||j==0){
                    if(i==2 && board[i+1][j-1]==' '){
                        return j;
                    }
                    if(board[i+1][j-1]==' ' && board[i+2][j-1]!=' '){
                        return j;
                    }
                }else if(i==3||j==4){
                    if(board[i-3][j+3]==' ' && board[i-2][j+3]!=' '){
                        return j+4;
                    }
                }else{
                    if((board[i+1][j-1]==' ' && board[i+2][j-1]!=' ') || (board[i-3][j+3]==' ' && board[i-2][j+3]!=' ')){
                        return (rand()%1000<=499)?j:j+4;
                    }else if(board[i+1][j-1]==' ' && board[i+2][j-1]!=' '){
                        return j;
                    }else if(board[i+1][j+3]==' ' && board[i+2][j+3]!=' '){
                        return j+4;
                    }
                }
            }
        }
    }
    //diagonal slope=(+)//
    for(i=2;i<6;i++){
        for(j=0;j<5;j++){
            if(board[i][j]==board[i-1][j+1]&&board[i-1][j+1]==board[i-2][j+2]&&board[i][j]!=' '){
                if((i==2&&j==0) || (i==5&&j==4)){
                    continue;
                }else if(i==2||j==4){
                    if(i==4 && board[i+1][j-1]==' '){
                        return j;
                    }
                    if(board[i+1][j-1]==' ' && board[i+2][j-1]!=' '){
                        return j;
                    }
                }else if(i==5||j==0){
                    if(board[i-3][j+3]==' ' && board[i-2][j+3]!=' '){
                        return j+4;
                    }
                }else{
                    if((board[i+1][j-1]==' ' && board[i+2][j-1]!=' ') || (board[i-3][j+3]==' ' && board[i-2][j+3]!=' ')){
                        return (rand()%1000<=499)?j:j+4;
                    }else if(board[i+1][j-1]==' ' && board[i+2][j-1]!=' '){
                        return j;
                    }else if(board[i+1][j+3]==' ' && board[i+2][j+3]!=' '){
                        return j+4;
                    }
                }
            }
        }
    }
    return rand()%6+1;
}

int main(){
    int i,j,input,option;
    for(i=0;i<6;i++){
        for(j=0;j<7;j++){
            board[i][j]=' ';
        }
    }
    printf("1. Single Player\n2. Two Players\nPlease input: ");
    scanf("%d",&option);
    while(option!=1 && option!=2){
        printf("Your input was invalid. \nPlease retype:");
        scanf("%d",&option);
    }
    switch(option){
        case 1:
            strcpy(player[0], "You");
            strcpy(player[1], "AI");
            do{
                clear();
                gnrt();
                printf("Round %d\nYour (%c) turn (input 1/2/3...):",r+1,player[r%2],(r%2==0)?'@':'O');
                scanf("%d",&input);
                while(!input_f(input)){
                    printf("Your input was invalid. \nPlease retype:");
                    scanf("%d",&input);
                }
                r++;
                if(checkwin()) break;
                input_f(ai());
                r++;
            }while(!checkwin());
            r=r-1;
            gnrt_s();
            break;
        case 2:
            for(i=0;i<2;i++){
                printf("Player %d:",i+1);
                fflush(stdin);
                gets(player[i]);
            }
            do{
                clear();
                gnrt();
                printf("Round %d\n%s's (%c) turn (input 1/2/3...):",r+1,player[r%2],(r%2==0)?'@':'O');
                scanf("%d",&input);
                while(!input_f(input)){
                    printf("Your input was invalid. \nPlease retype:");
                    scanf("%d",&input);
                }
                r++;
            }while(!checkwin());
            r=r-1;
            gnrt_s();
            break;
    }
}

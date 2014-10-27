#include <bits/stdc++.h>

using namespace std;

typedef long long     LL;
typedef pair<int,int> pii;

double PI  = acos(-1);
double EPS = 1e-7;
int INF    = 1000000000;
LL INFLL   = 1000000000000000000LL;

#define fi            first
#define se            second
#define mp            make_pair
#define pb            push_back

#define input(in)     freopen(in,"r",stdin)
#define output(out)   freopen(out,"w",stdout)

#define MIN(a, b)     (a) = min((a), (b))
#define MAX(a, b)     (a) = max((a), (b))

#define RESET(a, b)   memset(a,b,sizeof(a))
#define ALL(a)        (a).begin(), (a).end()
#define SIZE(a)       (int)a.size()
#define SORT(a)       sort(ALL(a))
#define UNIQUE(a)     (a).erase( unique( ALL(a) ), (a).end() )
#define FOR(a, b, c)  for (int (a)=(b); (a)<=(c); (a)++)
#define FORD(a, b, c) for (int (a)=(b); (a)>=(c); (a)--)
#define FORIT(a, b)   for (__typeof((b).begin()) a=(b).begin(); a!=(b).end(); a++)

int mx[8] = {-1,1,0,0,-1,-1,1,1};
int my[8] = {0,0,-1,1,-1,1,-1,1};

// ----- //

/*
	$('td:nth-child(15)').css("display", "none");
	$('td:nth-child(14)').css("display", "none");
	$('td:nth-child(13)').css("display", "none");
	$('td:nth-child(12)').css("display", "none");
	$('td:nth-child(11)').css("display", "none");
	$('td:nth-child(10)').css("display", "none");
	$('td:nth-child(9)').css("display", "none");
	$('td:nth-child(8)').css("display", "none");
	$('td:nth-child(7)').css("display", "none");
	$('td:nth-child(6)').css("display", "none");
	$('td:nth-child(5)').css("display", "none");
	$('td:nth-child(4)').css("display", "none");

*/

char contestname[1005];
char data[10005];
char handle[10005];

int main()
{
	scanf("%s\n",contestname);
	assert(isalpha(contestname[0]));

	string name = contestname;
	string out = name+".dbs";
	output(out.c_str());
	
	int rank,score,penalty,penalty1,penalty2;
	while(scanf("%d",&rank) != EOF)
	{
		while(getchar() != '\n');
		scanf("(%s %d",handle,&score);
		scanf("%d:%d",&penalty1,&penalty2);
		handle[strlen(handle)-1] = '\0';
		penalty = penalty1*60+penalty2;
		if (score > 0) printf("INSERT INTO %s VALUES (%d,\"%s\",%d,%d);\n",contestname,rank,handle,score,penalty);
	}
}
